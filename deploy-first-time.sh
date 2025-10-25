#!/bin/bash

# =====================================================
# ADABKITA FIRST-TIME DEPLOYMENT SCRIPT
# =====================================================
# Script untuk deployment PERTAMA KALI aplikasi AdabKita
# Includes: database creation, user seeding, dll
#
# CARA PENGGUNAAN:
# chmod +x deploy-first-time.sh
# ./deploy-first-time.sh
#
# AUTHOR: System
# VERSION: 1.0
# LAST UPDATE: 2025-10-26
# =====================================================

set -e

# =====================================================
# KONFIGURASI
# =====================================================
APP_NAME="AdabKita"
APP_DIR="/var/www/adabkita"
GIT_REPO="https://github.com/echal/adabkita.git"
GIT_BRANCH="main"
WEB_USER="www-data"
PHP_VERSION="8.2"

# Database Configuration (will be set in .env)
DB_NAME="adabkita_production"
DB_USER="adabkita_user"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# =====================================================
# HELPER FUNCTIONS
# =====================================================

print_header() {
    echo -e "\n${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# =====================================================
# WELCOME MESSAGE
# =====================================================

print_header "AdabKita First-Time Deployment"

echo "Script ini akan melakukan:"
echo "1. Clone repository dari Git"
echo "2. Install dependencies"
echo "3. Setup database"
echo "4. Generate APP_KEY"
echo "5. Run migrations"
echo "6. Seed user data"
echo "7. Set permissions"
echo "8. Configure web server"
echo ""
read -p "Lanjutkan? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit 1
fi

# =====================================================
# CHECK ROOT ACCESS
# =====================================================

if [[ $EUID -ne 0 ]]; then
   print_error "Script ini harus dijalankan dengan sudo/root"
   exit 1
fi

# =====================================================
# INSTALL DEPENDENCIES
# =====================================================

print_header "Installing System Dependencies"

print_info "Updating package list..."
apt-get update -qq

print_info "Installing required packages..."
apt-get install -y git curl wget unzip software-properties-common

# PHP and extensions
print_info "Installing PHP and extensions..."
apt-get install -y php$PHP_VERSION php$PHP_VERSION-{cli,fpm,mysql,mbstring,xml,curl,zip,gd,bcmath,intl}

# Composer
if ! command -v composer &> /dev/null; then
    print_info "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    print_success "Composer installed"
else
    print_success "Composer already installed"
fi

# MySQL
if ! command -v mysql &> /dev/null; then
    print_info "Installing MySQL..."
    apt-get install -y mysql-server
    systemctl start mysql
    systemctl enable mysql
    print_success "MySQL installed"
else
    print_success "MySQL already installed"
fi

print_success "All dependencies installed"

# =====================================================
# CLONE REPOSITORY
# =====================================================

print_header "Cloning Repository"

if [ -d "$APP_DIR" ]; then
    print_warning "Directory $APP_DIR already exists!"
    read -p "Remove and re-clone? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        rm -rf $APP_DIR
    else
        exit 1
    fi
fi

print_info "Cloning from $GIT_REPO..."
git clone -b $GIT_BRANCH $GIT_REPO $APP_DIR
cd $APP_DIR
print_success "Repository cloned"

# =====================================================
# DATABASE SETUP
# =====================================================

print_header "Database Setup"

print_warning "MySQL Root Password diperlukan untuk membuat database"
read -sp "MySQL Root Password: " MYSQL_ROOT_PASSWORD
echo

# Generate random password for database user
DB_PASSWORD=$(openssl rand -base64 16)

print_info "Creating database and user..."

# Create database and user
mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT

print_success "Database created: $DB_NAME"
print_success "User created: $DB_USER"
print_info "Password: $DB_PASSWORD (save this!)"

# =====================================================
# ENVIRONMENT SETUP
# =====================================================

print_header "Environment Configuration"

if [ -f "$APP_DIR/.env.production" ]; then
    cp $APP_DIR/.env.production $APP_DIR/.env
    print_success "Copied .env.production to .env"
else
    cp $APP_DIR/.env.example $APP_DIR/.env
    print_success "Copied .env.example to .env"
fi

# Update .env with database credentials
print_info "Updating .env with database credentials..."
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" $APP_DIR/.env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" $APP_DIR/.env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" $APP_DIR/.env
sed -i "s/APP_ENV=.*/APP_ENV=production/" $APP_DIR/.env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" $APP_DIR/.env

print_success ".env configured"

# Generate APP_KEY
print_info "Generating APP_KEY..."
php artisan key:generate --force
print_success "APP_KEY generated"

# =====================================================
# COMPOSER DEPENDENCIES
# =====================================================

print_header "Installing Composer Dependencies"

composer install --no-dev --optimize-autoloader --no-interaction
print_success "Dependencies installed"

# =====================================================
# FILE PERMISSIONS
# =====================================================

print_header "Setting File Permissions"

chown -R $WEB_USER:$WEB_USER $APP_DIR
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
print_success "Permissions set"

# =====================================================
# DATABASE MIGRATION & SEEDING
# =====================================================

print_header "Database Migration & Seeding"

print_info "Running migrations..."
php artisan migrate --force
print_success "Migrations completed"

print_info "Seeding initial data..."
php artisan db:seed --force
print_success "Database seeded"

# =====================================================
# OPTIMIZATION
# =====================================================

print_header "Optimization"

php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
print_success "Application optimized"

# =====================================================
# CREATE DEPLOYMENT INFO FILE
# =====================================================

print_header "Creating Deployment Info"

cat > $APP_DIR/DEPLOYMENT_INFO.txt <<EOF
AdabKita Deployment Information
================================

Deployed: $(date '+%Y-%m-%d %H:%M:%S')
Version: 1.0
Branch: $GIT_BRANCH

Database Information:
---------------------
Database Name: $DB_NAME
Database User: $DB_USER
Database Password: $DB_PASSWORD

IMPORTANT: Keep this file secure and DO NOT commit to Git!

Default Admin Credentials (change immediately):
-----------------------------------------------
Check database/seeders/UserSeeder.php for default users

Next Steps:
-----------
1. Configure web server (Apache/Nginx)
2. Install SSL certificate
3. Test application
4. Change default passwords
5. Setup backup automation
6. Configure monitoring

EOF

chmod 600 $APP_DIR/DEPLOYMENT_INFO.txt
print_success "Deployment info saved to DEPLOYMENT_INFO.txt"

# =====================================================
# DISPLAY CREDENTIALS
# =====================================================

print_header "Deployment Credentials"

echo -e "${GREEN}Database Information:${NC}"
echo -e "  Database: ${BLUE}$DB_NAME${NC}"
echo -e "  Username: ${BLUE}$DB_USER${NC}"
echo -e "  Password: ${YELLOW}$DB_PASSWORD${NC}"
echo ""
echo -e "${RED}⚠ SAVE THESE CREDENTIALS SECURELY!${NC}"
echo -e "Credentials juga tersimpan di: ${BLUE}$APP_DIR/DEPLOYMENT_INFO.txt${NC}"
echo ""

# =====================================================
# NEXT STEPS
# =====================================================

print_header "Next Steps"

echo "1. Configure Web Server:"
echo "   - Apache: Copy config dari server-configs/apache-adabkita.conf"
echo "   - Nginx: Copy config dari server-configs/nginx-adabkita.conf"
echo ""
echo "2. Install SSL Certificate:"
echo "   sudo certbot --apache -d adabkita.gaspul.com"
echo ""
echo "3. Test aplikasi:"
echo "   curl http://localhost"
echo ""
echo "4. Login ke aplikasi dan ganti password default"
echo ""
echo "5. Setup backup otomatis:"
echo "   ./backup-database.sh"
echo ""

print_success "First-time deployment completed! 🎉"

# =====================================================
# END OF SCRIPT
# =====================================================
