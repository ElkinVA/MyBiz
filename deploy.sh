#!/bin/bash

# MyBiz Production Deployment Script
set -e

echo "🚀 Starting MyBiz Production Deployment..."

# Configuration
PROJECT_DIR="/var/www/mybiz"
BACKUP_DIR="/var/backups/mybiz"
DATE=$(date +%Y%m%d_%H%M%S)

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Logging
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    error "Please run as root"
fi

# Backup current version
backup_current() {
    log "Backing up current version..."
    
    if [ -d "$PROJECT_DIR" ]; then
        tar -czf "$BACKUP_DIR/backup_$DATE.tar.gz" -C "$PROJECT_DIR" . || warn "Backup creation failed"
        log "Backup created: $BACKUP_DIR/backup_$DATE.tar.gz"
    else
        warn "Project directory doesn't exist, skipping backup"
    fi
}

# Deploy new version
deploy() {
    log "Deploying new version..."
    
    # Create project directory if it doesn't exist
    mkdir -p "$PROJECT_DIR"
    
    # Copy project files (assuming files are in current directory)
    rsync -av --delete \
        --exclude='.env' \
        --exclude='storage/logs/' \
        --exclude='public/assets/images/uploads/' \
        --exclude='node_modules' \
        --exclude='.git' \
        ./ "$PROJECT_DIR/"
    
    # Set proper permissions
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/public/assets/images/uploads"
    
    log "Files deployed successfully"
}

# Setup environment
setup_environment() {
    log "Setting up environment..."
    
    # Create necessary directories
    mkdir -p "$PROJECT_DIR/storage/logs"
    mkdir -p "$PROJECT_DIR/storage/cache"
    mkdir -p "$PROJECT_DIR/public/assets/images/uploads"
    mkdir -p "$BACKUP_DIR"
    
    # Set permissions
    chown -R www-data:www-data "$PROJECT_DIR/storage" "$PROJECT_DIR/public/assets/images/uploads"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/public/assets/images/uploads"
    
    # Copy environment file if it doesn't exist
    if [ ! -f "$PROJECT_DIR/.env" ]; then
        if [ -f ".env.production" ]; then
            cp ".env.production" "$PROJECT_DIR/.env"
            log "Environment file created"
        else
            warn "No .env.production file found"
        fi
    fi
}

# Database migrations
run_migrations() {
    log "Running database migrations..."
    
    # Check if database configuration exists
    if [ -f "$PROJECT_DIR/.env" ]; then
        cd "$PROJECT_DIR"
        
        # Import schema if needed
        if [ -f "database/schema.sql" ]; then
            mysql -h $(grep DB_HOST .env | cut -d '=' -f2) \
                  -u $(grep DB_USER .env | cut -d '=' -f2) \
                  -p$(grep DB_PASS .env | cut -d '=' -f2) \
                  $(grep DB_NAME .env | cut -d '=' -f2) < database/schema.sql || warn "Schema import failed"
        fi
        
        log "Database migrations completed"
    else
        warn "No .env file found, skipping migrations"
    fi
}

# Clear cache
clear_cache() {
    log "Clearing application cache..."
    
    if [ -d "$PROJECT_DIR/storage/cache" ]; then
        rm -rf "$PROJECT_DIR/storage/cache/*"
        log "Cache cleared"
    fi
}

# Restart services
restart_services() {
    log "Restarting web services..."
    
    # Restart PHP-FPM
    systemctl restart php8.1-fpm || warn "PHP-FPM restart failed"
    
    # Restart Nginx/Apache
    if systemctl is-active --quiet nginx; then
        systemctl reload nginx || error "Nginx reload failed"
        log "Nginx reloaded"
    elif systemctl is-active --quiet apache2; then
        systemctl reload apache2 || error "Apache reload failed"
        log "Apache reloaded"
    fi
    
    log "Services restarted successfully"
}

# Health check
health_check() {
    log "Performing health check..."
    
    # Check if application responds
    response=$(curl -s -o /dev/null -w "%{http_code}" https://mybiz-shop.ru/health)
    
    if [ "$response" -eq 200 ]; then
        log "Health check passed (HTTP 200)"
    else
        error "Health check failed (HTTP $response)"
    fi
}

# Cleanup old backups
cleanup_backups() {
    log "Cleaning up old backups..."
    
    find "$BACKUP_DIR" -name "backup_*.tar.gz" -mtime +30 -delete || warn "Backup cleanup failed"
    log "Old backups cleaned up"
}

# Main deployment process
main() {
    log "Starting deployment process..."
    
    backup_current
    deploy
    setup_environment
    run_migrations
    clear_cache
    restart_services
    health_check
    cleanup_backups
    
    log "🎉 Deployment completed successfully!"
}

# Run main function
main