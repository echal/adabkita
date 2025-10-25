#!/bin/bash

# =====================================================
# DATABASE BACKUP SCRIPT
# AdabKita - Platform Pembelajaran Adab Islami
# =====================================================
# Script untuk backup database secara otomatis
#
# CARA PENGGUNAAN MANUAL:
# chmod +x backup-database.sh
# ./backup-database.sh
#
# SETUP CRON JOB (backup otomatis setiap hari jam 2 pagi):
# crontab -e
# Tambahkan: 0 2 * * * /var/www/adabkita/backup-database.sh
#
# =====================================================

set -e

# =====================================================
# CONFIGURATION
# =====================================================

# Baca dari .env file
if [ -f ".env" ]; then
    export $(cat .env | grep -v '^#' | grep DB_ | xargs)
fi

# Default values jika tidak ada di .env
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-adabkita_production}
DB_USERNAME=${DB_USERNAME:-adabkita_user}
DB_PASSWORD=${DB_PASSWORD}

# Backup settings
BACKUP_DIR="/backup/adabkita/database"
KEEP_DAYS=30  # Simpan backup 30 hari
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/adabkita_${DB_DATABASE}_${DATE}.sql"
BACKUP_FILE_GZ="${BACKUP_FILE}.gz"

# Email notification (optional)
SEND_EMAIL=false
EMAIL_TO="admin@adabkita.gaspul.com"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# =====================================================
# HELPER FUNCTIONS
# =====================================================

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ $1${NC}"; }

log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$BACKUP_DIR/backup.log"
}

send_email() {
    if [ "$SEND_EMAIL" = true ]; then
        echo "$2" | mail -s "$1" "$EMAIL_TO"
    fi
}

# =====================================================
# PRE-BACKUP CHECKS
# =====================================================

print_info "Starting database backup..."
log_message "Starting backup for database: $DB_DATABASE"

# Check if mysqldump is available
if ! command -v mysqldump &> /dev/null; then
    print_error "mysqldump not found. Please install mysql-client"
    log_message "ERROR: mysqldump not found"
    exit 1
fi

# Check if database credentials are set
if [ -z "$DB_PASSWORD" ]; then
    print_error "DB_PASSWORD not set in .env"
    log_message "ERROR: DB_PASSWORD not set"
    exit 1
fi

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"
if [ ! -d "$BACKUP_DIR" ]; then
    print_error "Failed to create backup directory: $BACKUP_DIR"
    log_message "ERROR: Failed to create backup directory"
    exit 1
fi

# =====================================================
# PERFORM BACKUP
# =====================================================

print_info "Backing up database: $DB_DATABASE"
print_info "Backup file: $BACKUP_FILE_GZ"

# Create MySQL backup
if mysqldump \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user="$DB_USERNAME" \
    --password="$DB_PASSWORD" \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    --add-drop-table \
    --add-locks \
    --extended-insert \
    --quick \
    "$DB_DATABASE" > "$BACKUP_FILE" 2>/dev/null; then

    # Compress backup
    gzip -f "$BACKUP_FILE"

    # Get file size
    FILE_SIZE=$(du -h "$BACKUP_FILE_GZ" | cut -f1)

    print_success "Backup completed successfully!"
    print_info "Backup file: $BACKUP_FILE_GZ ($FILE_SIZE)"

    log_message "SUCCESS: Backup completed - $BACKUP_FILE_GZ ($FILE_SIZE)"
    send_email "AdabKita Backup Success" "Database backup completed successfully.\nFile: $BACKUP_FILE_GZ\nSize: $FILE_SIZE"

else
    print_error "Backup failed!"
    log_message "ERROR: Backup failed for database $DB_DATABASE"
    send_email "AdabKita Backup FAILED" "Database backup failed!\nDatabase: $DB_DATABASE\nTime: $(date)"
    exit 1
fi

# =====================================================
# CLEANUP OLD BACKUPS
# =====================================================

print_info "Cleaning up old backups (older than $KEEP_DAYS days)..."

# Count files before cleanup
BEFORE_COUNT=$(find "$BACKUP_DIR" -name "*.sql.gz" -type f | wc -l)

# Delete old backups
find "$BACKUP_DIR" -name "*.sql.gz" -type f -mtime +$KEEP_DAYS -delete

# Count files after cleanup
AFTER_COUNT=$(find "$BACKUP_DIR" -name "*.sql.gz" -type f | wc -l)
DELETED_COUNT=$((BEFORE_COUNT - AFTER_COUNT))

if [ $DELETED_COUNT -gt 0 ]; then
    print_success "Deleted $DELETED_COUNT old backup(s)"
    log_message "Cleanup: Deleted $DELETED_COUNT old backup(s)"
else
    print_info "No old backups to delete"
fi

# =====================================================
# BACKUP STATISTICS
# =====================================================

print_info "Backup Statistics:"
echo "  Total backups: $AFTER_COUNT"
echo "  Latest backup: $BACKUP_FILE_GZ"
echo "  Backup size: $FILE_SIZE"
echo "  Retention: $KEEP_DAYS days"

# Calculate total backup size
TOTAL_SIZE=$(du -sh "$BACKUP_DIR" | cut -f1)
echo "  Total backup directory size: $TOTAL_SIZE"

log_message "Statistics: Total backups=$AFTER_COUNT, Total size=$TOTAL_SIZE"

# =====================================================
# VERIFY BACKUP (Optional)
# =====================================================

print_info "Verifying backup integrity..."

# Check if backup file is valid gzip
if gzip -t "$BACKUP_FILE_GZ" 2>/dev/null; then
    print_success "Backup file integrity: OK"
    log_message "Verification: Backup file integrity OK"
else
    print_error "Backup file is corrupted!"
    log_message "ERROR: Backup file corrupted - $BACKUP_FILE_GZ"
    send_email "AdabKita Backup CORRUPTED" "Backup file is corrupted!\nFile: $BACKUP_FILE_GZ"
    exit 1
fi

# =====================================================
# BACKUP TO REMOTE (Optional)
# =====================================================

# Uncomment jika ingin backup ke remote server via rsync
# REMOTE_USER="backup"
# REMOTE_HOST="backup-server.com"
# REMOTE_DIR="/backup/adabkita"
#
# print_info "Syncing to remote backup server..."
# rsync -avz --delete "$BACKUP_DIR/" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR/"
# if [ $? -eq 0 ]; then
#     print_success "Remote backup sync: OK"
#     log_message "Remote sync: SUCCESS"
# else
#     print_error "Remote backup sync: FAILED"
#     log_message "ERROR: Remote sync failed"
# fi

# =====================================================
# BACKUP TO S3 (Optional)
# =====================================================

# Uncomment jika ingin backup ke AWS S3
# S3_BUCKET="s3://adabkita-backups/database"
#
# if command -v aws &> /dev/null; then
#     print_info "Uploading to S3..."
#     aws s3 cp "$BACKUP_FILE_GZ" "$S3_BUCKET/" --storage-class GLACIER
#     if [ $? -eq 0 ]; then
#         print_success "S3 upload: OK"
#         log_message "S3 upload: SUCCESS"
#     else
#         print_error "S3 upload: FAILED"
#         log_message "ERROR: S3 upload failed"
#     fi
# fi

# =====================================================
# SUMMARY
# =====================================================

print_success "Backup process completed!"
echo ""
echo "Backup Details:"
echo "  Database: $DB_DATABASE"
echo "  File: $BACKUP_FILE_GZ"
echo "  Size: $FILE_SIZE"
echo "  Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""
echo "To restore this backup:"
echo "  gunzip < $BACKUP_FILE_GZ | mysql -u $DB_USERNAME -p $DB_DATABASE"
echo ""

log_message "Backup process completed successfully"

# =====================================================
# END OF SCRIPT
# =====================================================
