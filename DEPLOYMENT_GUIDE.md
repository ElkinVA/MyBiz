# MyBiz Production Deployment Guide

## Предварительные требования

### Серверные требования
- Ubuntu 20.04 LTS или выше
- PHP 8.1+ с расширениями:
  - php8.1-fpm, php8.1-mysql, php8.1-curl, php8.1-gd, php8.1-mbstring
  - php8.1-xml, php8.1-zip, php8.1-bcmath
- MySQL 8.0+ или MariaDB 10.4+
- Nginx 1.18+ или Apache 2.4+
- SSL сертификат (Let's Encrypt)

### Настройка сервера

1. **Обновление системы**
```bash
apt update && apt upgrade -y