#!/bin/bash

# =====================================================
# DATABASE SETUP SCRIPT
# AdabKita - Platform Pembelajaran Adab Islami
# =====================================================
# Script untuk setup database production
#
# CARA PENGGUNAAN:
# chmod +x database-setup.sh
# ./database-setup.sh
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
print_header() {
    echo -e "\n${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}\n"
}

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ $1${NC}"; }

# =====================================================
# MAIN SCRIPT
# =====================================================

print_header "AdabKita Database Setup"

# Get database credentials
read -p "Database Name (default: adabkita_production): " DB_NAME
DB_NAME=${DB_NAME:-adabkita_production}

read -p "Database Username (default: adabkita_user): " DB_USER
DB_USER=${DB_USER:-adabkita_user}

# Generate secure password
DB_PASSWORD=$(openssl rand -base64 16)
read -sp "Database Password (tekan Enter untuk generate otomatis): " INPUT_PASSWORD
echo
if [ -n "$INPUT_PASSWORD" ]; then
    DB_PASSWORD=$INPUT_PASSWORD
fi

read -p "Database Host (default: localhost): " DB_HOST
DB_HOST=${DB_HOST:-localhost}

# Confirm
echo ""
print_info "Database Configuration:"
echo "  Name: $DB_NAME"
echo "  User: $DB_USER"
echo "  Host: $DB_HOST"
echo "  Password: $DB_PASSWORD"
echo ""
read -p "Lanjutkan dengan konfigurasi ini? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit 1
fi

# Get MySQL root password
print_warning "MySQL Root Password diperlukan"
read -sp "MySQL Root Password: " MYSQL_ROOT_PASSWORD
echo

# =====================================================
# CREATE DATABASE AND USER
# =====================================================

print_header "Creating Database and User"

mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<MYSQL_SCRIPT || {
    print_error "Gagal connect ke MySQL. Periksa password root."
    exit 1
}

-- Create database
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER IF NOT EXISTS '$DB_USER'@'$DB_HOST'
    IDENTIFIED BY '$DB_PASSWORD';

-- Grant privileges
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.*
    TO '$DB_USER'@'$DB_HOST';

-- Grant specific privileges only (more secure)
-- GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, TRIGGER
-- ON \`$DB_NAME\`.*
-- TO '$DB_USER'@'$DB_HOST';

FLUSH PRIVILEGES;

-- Show databases
SHOW DATABASES LIKE '$DB_NAME';

MYSQL_SCRIPT

print_success "Database dan user berhasil dibuat!"

# =====================================================
# SAVE CREDENTIALS
# =====================================================

print_header "Saving Credentials"

cat > database-credentials.txt <<EOF
AdabKita Database Credentials
=============================

Created: $(date '+%Y-%m-%d %H:%M:%S')

Database Information:
--------------------
Host: $DB_HOST
Database: $DB_NAME
Username: $DB_USER
Password: $DB_PASSWORD

MySQL Connection String:
-----------------------
mysql -h $DB_HOST -u $DB_USER -p'$DB_PASSWORD' $DB_NAME

Laravel .env Configuration:
---------------------------
DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASSWORD

IMPORTANT:
- Keep this file secure!
- DO NOT commit to Git!
- Backup this file to secure location

EOF

chmod 600 database-credentials.txt
print_success "Credentials saved to: database-credentials.txt"

# =====================================================
# TEST CONNECTION
# =====================================================

print_header "Testing Database Connection"

if mysql -h $DB_HOST -u $DB_USER -p"$DB_PASSWORD" -e "USE $DB_NAME; SELECT 1;" > /dev/null 2>&1; then
    print_success "Database connection: OK"
else
    print_error "Database connection: FAILED"
    exit 1
fi

# =====================================================
# SUMMARY
# =====================================================

print_header "Setup Complete!"

echo -e "${GREEN}Database berhasil dibuat:${NC}"
echo -e "  Host: ${BLUE}$DB_HOST${NC}"
echo -e "  Database: ${BLUE}$DB_NAME${NC}"
echo -e "  Username: ${BLUE}$DB_USER${NC}"
echo -e "  Password: ${YELLOW}$DB_PASSWORD${NC}"
echo ""
echo -e "${YELLOW}⚠ SIMPAN CREDENTIALS INI DENGAN AMAN!${NC}"
echo -e "Credentials tersimpan di: ${BLUE}database-credentials.txt${NC}"
echo ""
echo -e "${GREEN}Next Steps:${NC}"
echo "1. Update .env file dengan database credentials"
echo "2. Run migrations: php artisan migrate"
echo "3. Seed data: php artisan db:seed"
echo "4. Setup backup: ./backup-database.sh"
echo ""

# =====================================================
# END OF SCRIPT
# =====================================================
