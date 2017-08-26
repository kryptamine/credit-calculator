Credit calculator
===================


----------
## Nginx config example ##

```
#!nginx

server {
    charset utf-8;
    client_max_body_size 128M;

    listen 80;

    server_name credit-calculator.dev;
    root        /var/www/credit-calculator/public;
    index       index.php;

    access_log  /var/log/nginx/credit-calculator.dev.access.log;
    error_log   /var/log/nginx/credit-calculator.dev.error.log;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        # your php settings
        try_files $uri =404;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}

```

## Init ##

```
#!

composer install

php artisan migrate

php artisan vendor:publish

npm install

npm run dev (or prod)
```