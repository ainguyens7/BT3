# Ali Reviews - Shopify app

## 1. Guideline about shopify app

This guide describes the basic steps for getting started with Shopify's API: signing up for a Shopify Partner account,
creating a development store where you can test your app, generating the required API credentials,
and then making API calls to Shopify against a single shop. [Read more](https://github.com/YoungWorldTechnology/shopify-app-development-document).

## 2. Install project

**Clone repo**

```
$ git clone git@github.com:YoungWorldTechnology/alireviews.git alireviews
```

**Install component**

Copy `.env.example` to `.env`

```
$ cd /path/to/alireviews
```

Install php compoments

```
$ composer install
```

Install front end components

```
$ npm install
```

```
$ bower install
```

## 3. Config project

**Generate APP_KEY**

```
$ php artisan key:generate
```

`APP_ENV` is `development`, or `staging`, or `production`.

`APP_URL` is ngrok url.

Laravel have supported many database, but alireviews use mysql as primary database, one for manage shops and comments, one for manage products.

Change your config database manage shops and comments

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alireview
DB_USERNAME=root
DB_PASSWORD=root
```

Change your config database manage productions

```
DB_HOST_PRODUCT=127.0.0.1
DB_PORT_PRODUCT=3306
DB_DATABASE_PRODUCT=alireview_products
DB_USERNAME_PRODUCT=root
DB_PASSWORD_PRODUCT=root
```

Because alireviews heavy depend on [chrome extension](https://chrome.google.com/webstore/detail/ali-reviews-aliexpress-re/bbaogjaeflnjolejjcpceoapngapnbaj?hl=en), install that extension and config `EXTENSION_ID` with extension id, [what is chrome extension id](https://stackoverflow.com/questions/8946325/chrome-extension-id-how-to-find-it).

Don't remove config sentry `SENTRY_DSN`.

Config your Shopify app with `API_KEY` and `API_SECRET`

```
API_KEY=SHOPIFY_API_KEY
API_SECRET=SHOPIFY_SECRET_KEY
```

Config account PUSHER

```
PUSHER_APP_ID={your_app_id}
PUSHER_APP_KEY={your_app_key}
PUSHER_APP_SECRET={your_app_secret}
PUSHER_APP_CLUSTER={your_app_cluster}
```

Change your config BROADCAST DRIVER

```
BROADCAST_DRIVER=pusher
```

## 4. Add custom proxy

[Offical tutorials app proxies](https://help.shopify.com/api/tutorials/application-proxies)

Add proxy to Shopify app with `Sub path prefix` and `Sub path` are anything, and Proxy URL must have path `/comment`.

## 5. Run project

Project run on [Laravel 5.4](https://laravel.com/docs/5.4/), php7.\*, and node8.\*

**Migrate schame database**

```
$ php artisan migrate:install && php artisan migrate
```

**Run jobs worker**

[Laravel jobs worker](https://laravel.com/docs/5.4/queues)

```
$ php artisan queue:work
```

# Contributing guideline

Simple workflow:

```
code -> push to branch -> create PR to target branch -> reviews code -> deploy to staging -> testing -> deploy production
```

Every feature, bug fix must have check out from master, use simple workflow above, no one can force push to master except admin. Hot fix can directly push to master. Before push to customer branch, we must `pull rebase` target branch.
Every commit must have naming and easy to read or understand.

## Updating

### Cache frontend

Default use redis to cache frontend, change connection in file `.env` reflect with server

```
REDIS_HOST_CACHE_FRONTEND=127.0.0.1
REDIS_PASSWORD_CACHE_FRONTEND=null
REDIS_PORT_CACHE_FRONTEND=6379
DEFAULT_CACHE_FRONTEND_EXPIRED=3600
```

### Inject environment variables in your .env 
[Laravel Mix](https://laravel.com/docs/5.4/mix)

```
MIX_APP_URL=https://example.com
```
### CDN static

Ali Reviews use cdn to serve static content, config cdn static

```
CDN_STATIC=https://example.com
```
