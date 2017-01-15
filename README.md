###Why is this useful?

The RedisStore that comes with the Laravel Cache does not compress string values out of the box.
  The RedisStore in this package does. Caching string values can save a ton of memory and/or network bandwidth depending on 
  cached item size and request frequency.

###How do I use it?

Add a [custom cache driver](https://laravel.com/docs/5.3/cache#adding-custom-cache-drivers), like this...

```php
    public function boot()
    {
        Cache::extend('ehann-redis', function ($app) {
            return Cache::repository(new \Ehann\Cache\RedisStore(
                $app['redis'],
                $app['config']['cache.prefix'],
                $app['config']['cache.stores.redis.connection']
            ));
        });
    }
```

Add the **ehann-redis** custom driver to the redis store config in config/cache.php...

```php
    'stores' => [
        'redis' => [
            'driver' => 'ehann-redis',
        ],
    ],
```
