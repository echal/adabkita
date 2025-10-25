#!/bin/bash

# =====================================================
# DATABASE RESTORE SCRIPT
# AdabKita - Platform Pembelajaran Adab Islami
# =====================================================
# Script untuk restore database dari backup
#
# CARA PENGGUNAAN:
# chmod +x restore-database.sh
# ./restore-database.sh <backup-file.sql.gz>
#
# CONTOH:
# ./restore-database.sh /backup/adabkita/database/adabkita_20251026_020000.sql.gz
#
# =====================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Helper functions
print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ $1${NC}"; }

# =====================================================
# CHECK ARGUMENTS
# =====================================================

if [ $# -eq 0 ]; then
    print_error "Usage: $0 <backup-file.sql.gz>"
    echo ""
    echo "Available backups:"
    ls -lh /backup/adabkita/database/*.sql.gz 2>/dev/null || echo "No backups found"
    exit 1
fi

BACKUP_FILE="$1"

# Check if file exists
if [ ! -f "$BACKUP_FILE" ]; then
    print_error "Backup file not found: $BACKUP_FILE"
    exit 1
fi

# =====================================================
# READ DATABASE CREDENTIALS
# =====================================================

if [ -f ".env" ]; then
    export $(cat .env | grep -v '^#' | grep DB_ | xargs)
fi

DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-adabkita_production}
DB_USERNAME=${DB_USERNAME:-adabkita_user}
DB_PASSWORD=${DB_PASSWORD}

# =====================================================
# CONFIRMATION
# =====================================================

print_warning "DATABASE RESTORE WARNING!"
echo ""
echo "This will:"
echo "  1. OVERWRITE existing database: $DB_DATABASE"
echo "  2. All current data will be LOST"
echo "  3. Data will be replaced with backup from: $(basename $BACKUP_FILE)"
echo ""
print_warning "THIS ACTION CANNOT BE UNDONE!"
echo ""
read -p "Are you ABSOLUTELY SURE? Type 'YES' to continue: " CONFIRM

if [ "$CONFIRM" != "YES" ]; then
    print_info "Restore cancelled"
    exit 0
fi

# =====================================================
# PRE-RESTORE BACKUP
# =====================================================

print_info "Creating safety backup before restore..."

SAFETY_BACKUP="/tmp/adabkita_pre_restore_$(date +%Y%m%d_%H%M%S).sql.gz"

mysqldump \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user="$DB_USERNAME" \
    --password="$DB_PASSWORD" \
    --single-transaction \
    "$DB_DATABASE" | gzip > "$SAFETY_BACKUP"

print_success "Safety backup created: $SAFETY_BACKUP"

# =====================================================
# VERIFY BACKUP FILE
# =====================================================

print_info "Verifying backup file integrity..."

if ! gzip -t "$BACKUP_FILE" 2>/dev/null; then
    print_error "Backup file is corrupted!"
    exit 1
fi

print_success "Backup file integrity: OK"

# Get backup file info
FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
print_info "Backup file size: $FILE_SIZE"

# =====================================================
# PERFORM RESTORE
# =====================================================

print_info "Restoring database..."
print_warning "This may take several minutes..."

# Drop and recreate database (clean restore)
read -p "Drop and recreate database? (recommended) [Y/n]: " DROP_DB
DROP_DB=${DROP_DB:-Y}

if [[ $DROP_DB =~ ^[Yy]$ ]]; then
    print_info "Dropping database..."
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" <<MYSQL_SCRIPT
DROP DATABASE IF EXISTS \`$DB_DATABASE\`;
CREATE DATABASE \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
MYSQL_SCRIPT
    print_success "Database recreated"
fi

# Restore from backup
print_info "Importing data..."

if gunzip < "$BACKUP_FILE" | mysql \
    -h "$DB_HOST" \
    -P "$DB_PORT" \
    -u "$DB_USERNAME" \
    -p"$DB_PASSWORD" \
    "$DB_DATABASE" 2>/dev/null; then

    print_success "Database restored successfully!"

else
    print_error "Restore failed!"
    print_info "Rolling back to safety backup..."

    # Restore from safety backup
    gunzip < "$SAFETY_BACKUP" | mysql \
        -h "$DB_HOST" \
        -P "$DB_PORT" \
        -u "$DB_USERNAME" \
        -p"$DB_PASSWORD" \
        "$DB_DATABASE"

    print_success "Rolled back to previous state"
    exit 1
fi

# =====================================================
# POST-RESTORE VERIFICATION
# =====================================================

print_info "Verifying restore..."

# Count tables
TABLE_COUNT=$(mysql \
    -h "$DB_HOST" \
    -P "$DB_PORT" \
    -u "$DB_USERNAME" \
    -p"$DB_PASSWORD" \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_DATABASE';" \
    -s -N)

print_info "Tables restored: $TABLE_COUNT"

if [ "$TABLE_COUNT" -gt 0 ]; then
    print_success "Restore verification: OK"
else
    print_error "Restore verification: FAILED (no tables found)"
    exit 1
fi

# =====================================================
# CLEANUP
# =====================================================

print_info "Cleanup..."

# Ask to keep safety backup
read -p "Keep safety backup? [Y/n]: " KEEP_BACKUP
KEEP_BACKUP=${KEEP_BACKUP:-Y}

if [[ ! $KEEP_BACKUP =~ ^[Yy]$ ]]; then
    rm "$SAFETY_BACKUP"
    print_info "Safety backup deleted"
else
    print_success "Safety backup kept at: $SAFETY_BACKUP"
fi

# =====================================================
# POST-RESTORE TASKS
# =====================================================

print_info "Running post-restore tasks..."

# Clear Laravel cache
if [ -f "artisan" ]; then
    php artisan cache:clear > /dev/null 2>&1
    php artisan config:clear > /dev/null 2>&1
    print_success "Laravel cache cleared"
fi

# =====================================================
# SUMMARY
# =====================================================

print_success "Database restore completed!"
echo ""
echo "Restore Summary:"
echo "  Database: $DB_DATABASE"
echo "  Restored from: $(basename $BACKUP_FILE)"
echo "  Tables restored: $TABLE_COUNT"
echo "  Restore time: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""
print_warning "IMPORTANT: Test your application thoroughly!"
echo ""
echo "Next steps:"
echo "  1. Test application functionality"
echo "  2. Verify data integrity"
echo "  3. Check application logs"
echo "  4. Remove safety backup if everything OK"
echo ""

# =====================================================
# END OF SCRIPT
# =====================================================
