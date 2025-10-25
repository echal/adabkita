#!/bin/bash

# =====================================================
# ADABKITA DEPLOYMENT SCRIPT
# =====================================================
# Script untuk deployment aplikasi AdabKita ke production
#
# CARA PENGGUNAAN:
# chmod +x deploy.sh
# ./deploy.sh
#
# AUTHOR: System
# VERSION: 1.0
# LAST UPDATE: 2025-10-26
# =====================================================

set -e  # Exit immediately if a command exits with a non-zero status

# =====================================================
# KONFIGURASI
# =====================================================
APP_NAME="AdabKita"
APP_DIR="/var/www/adabkita"
GIT_REPO="https://github.com/echal/adabkita.git"
GIT_BRANCH="main"
WEB_USER="www-data"  # Ganti jika menggunakan user lain (misal: apache, nginx)
PHP_VERSION="8.2"    # Sesuaikan dengan versi PHP server

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

check_command() {
    if ! command -v $1 &> /dev/null; then
        print_error "$1 tidak ditemukan. Silakan install terlebih dahulu."
        exit 1
    fi
}

# =====================================================
# PRE-DEPLOYMENT CHECKS
# =====================================================

print_header "Pre-Deployment Checks"

# Check if running as root or with sudo
if [[ $EUID -ne 0 ]]; then
   print_warning "Script ini sebaiknya dijalankan dengan sudo untuk permission."
   read -p "Lanjutkan tanpa sudo? (y/n) " -n 1 -r
   echo
   if [[ ! $REPLY =~ ^[Yy]$ ]]; then
       exit 1
   fi
fi

# Check required commands
print_info "Memeriksa dependencies..."
check_command "git"
check_command "php"
check_command "composer"
check_command "mysql"
print_success "Semua dependencies tersedia"

# Check PHP version
PHP_CURRENT_VERSION=$(php -r "echo PHP_VERSION;" | cut -d'.' -f1,2)
print_info "PHP Version: $PHP_CURRENT_VERSION"

# =====================================================
# MAINTENANCE MODE
# =====================================================

print_header "Mengaktifkan Maintenance Mode"

if [ -d "$APP_DIR" ]; then
    cd $APP_DIR
    php artisan down --retry=60 --render="errors::503"
    print_success "Maintenance mode aktif"
else
    print_info "Direktori aplikasi belum ada, skip maintenance mode"
fi

# =====================================================
# GIT OPERATIONS
# =====================================================

print_header "Git Operations"

if [ -d "$APP_DIR/.git" ]; then
    print_info "Pulling latest changes from Git..."
    cd $APP_DIR
    git fetch origin
    git reset --hard origin/$GIT_BRANCH
    git pull origin $GIT_BRANCH
    print_success "Git pull selesai"
else
    print_info "Cloning repository..."
    git clone -b $GIT_BRANCH $GIT_REPO $APP_DIR
    cd $APP_DIR
    print_success "Repository cloned"
fi

# =====================================================
# COMPOSER DEPENDENCIES
# =====================================================

print_header "Installing Composer Dependencies"

cd $APP_DIR

# Check if composer.lock exists
if [ -f "composer.lock" ]; then
    print_info "Running composer install..."
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
else
    print_info "Running composer update..."
    composer update --no-dev --no-interaction --prefer-dist --optimize-autoloader
fi

print_success "Composer dependencies installed"

# =====================================================
# ENVIRONMENT CONFIGURATION
# =====================================================

print_header "Environment Configuration"

# Check if .env exists
if [ ! -f "$APP_DIR/.env" ]; then
    print_warning ".env file tidak ditemukan!"

    # Check if .env.production exists
    if [ -f "$APP_DIR/.env.production" ]; then
        print_info "Copying .env.production ke .env..."
        cp $APP_DIR/.env.production $APP_DIR/.env

        print_warning "PENTING: Edit file .env dan sesuaikan konfigurasi!"
        print_warning "Terutama: DB_PASSWORD, APP_URL, MAIL_* settings"
        read -p "Tekan Enter setelah selesai edit .env..." -r

        # Generate APP_KEY
        print_info "Generating APP_KEY..."
        php artisan key:generate --force
        print_success "APP_KEY generated"
    else
        print_error ".env.production tidak ditemukan!"
        print_info "Copying dari .env.example..."
        cp $APP_DIR/.env.example $APP_DIR/.env

        print_warning "EDIT .env file sekarang!"
        read -p "Tekan Enter setelah selesai edit .env..." -r

        php artisan key:generate --force
    fi
else
    print_success ".env file sudah ada"
fi

# =====================================================
# FILE PERMISSIONS
# =====================================================

print_header "Setting File Permissions"

print_info "Mengubah ownership ke $WEB_USER..."
if [[ $EUID -eq 0 ]]; then
    chown -R $WEB_USER:$WEB_USER $APP_DIR
    print_success "Ownership changed to $WEB_USER"
else
    print_warning "Skip ownership change (butuh root access)"
fi

print_info "Setting directory permissions..."
find $APP_DIR/storage -type d -exec chmod 775 {} \;
find $APP_DIR/bootstrap/cache -type d -exec chmod 775 {} \;

print_info "Setting file permissions..."
find $APP_DIR/storage -type f -exec chmod 664 {} \;
find $APP_DIR/bootstrap/cache -type f -exec chmod 664 {} \;

print_success "File permissions set"

# =====================================================
# DATABASE MIGRATION
# =====================================================

print_header "Database Migration"

print_info "Checking database connection..."
if php artisan migrate:status > /dev/null 2>&1; then
    print_success "Database connection OK"

    print_info "Running migrations..."
    php artisan migrate --force
    print_success "Migrations completed"
else
    print_error "Database connection failed!"
    print_warning "Periksa konfigurasi database di .env"
    exit 1
fi

# =====================================================
# OPTIMIZATION
# =====================================================

print_header "Optimization"

# Clear all caches
print_info "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared"

# Rebuild caches
print_info "Building production caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Production caches built"

# Optimize autoloader
print_info "Optimizing autoloader..."
composer dump-autoload --optimize
print_success "Autoloader optimized"

# =====================================================
# QUEUE & SCHEDULE (Optional)
# =====================================================

print_header "Queue & Schedule Configuration (Optional)"

# Check if queue workers are running
if pgrep -f "artisan queue:work" > /dev/null; then
    print_info "Restarting queue workers..."
    php artisan queue:restart
    print_success "Queue workers restarted"
else
    print_warning "Queue workers tidak berjalan"
    print_info "Untuk menjalankan queue: php artisan queue:work --daemon"
fi

# =====================================================
# DISABLE MAINTENANCE MODE
# =====================================================

print_header "Disabling Maintenance Mode"

php artisan up
print_success "Aplikasi sudah online!"

# =====================================================
# POST-DEPLOYMENT CHECKS
# =====================================================

print_header "Post-Deployment Checks"

# Check Laravel installation
print_info "Checking Laravel status..."
if php artisan --version > /dev/null 2>&1; then
    print_success "Laravel: $(php artisan --version)"
else
    print_error "Laravel check failed!"
fi

# Check storage permissions
if [ -w "$APP_DIR/storage/logs" ]; then
    print_success "Storage writable: OK"
else
    print_error "Storage NOT writable!"
fi

# Check .env exists
if [ -f "$APP_DIR/.env" ]; then
    print_success ".env file: EXISTS"
else
    print_error ".env file: MISSING"
fi

# Check APP_KEY set
if grep -q "APP_KEY=base64:" "$APP_DIR/.env"; then
    print_success "APP_KEY: SET"
else
    print_error "APP_KEY: NOT SET"
fi

# =====================================================
# DEPLOYMENT SUMMARY
# =====================================================

print_header "Deployment Summary"

echo -e "Application: ${GREEN}$APP_NAME${NC}"
echo -e "Directory: ${BLUE}$APP_DIR${NC}"
echo -e "Branch: ${BLUE}$GIT_BRANCH${NC}"
echo -e "PHP Version: ${BLUE}$PHP_CURRENT_VERSION${NC}"
echo -e "Status: ${GREEN}DEPLOYED${NC}"
echo -e "Time: ${BLUE}$(date '+%Y-%m-%d %H:%M:%S')${NC}"

print_success "\nDeployment completed successfully!"

# =====================================================
# NEXT STEPS
# =====================================================

print_header "Next Steps"

echo "1. Test aplikasi di browser:"
echo "   https://adabkita.gaspul.com"
echo ""
echo "2. Check logs untuk errors:"
echo "   tail -f $APP_DIR/storage/logs/laravel.log"
echo ""
echo "3. Monitor performance:"
echo "   htop atau top"
echo ""
echo "4. Backup database:"
echo "   ./backup-database.sh"
echo ""

print_success "Happy deploying! 🚀"

# =====================================================
# END OF SCRIPT
# =====================================================
