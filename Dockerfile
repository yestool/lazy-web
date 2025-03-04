# 构建阶段
FROM composer:2 AS builder

WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

# 生产阶段
FROM php:8.3-fpm

# 安装PHP扩展和依赖
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 复制项目文件
WORKDIR /var/www/html
COPY --from=builder /app .

# 设置权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/public

# 创建数据库目录并设置权限
RUN mkdir -p /var/www/html/database \
    && chown -R www-data:www-data /var/www/html/database \
    && chmod -R 755 /var/www/html/database

# 创建启动脚本
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# 暴露PHP-FPM端口
EXPOSE 9000

# 启动Nginx和PHP-FPM
CMD ["/usr/local/bin/start.sh"]