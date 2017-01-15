<?php

use Mockery as m;
use Illuminate\Cache\CacheManager;
use Illuminate\Redis\Database as RedisDatabase;
use Ehann\Cache\RedisStore;

class CacheManagerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testRedisStoreHasCompressionDisabledByDefault()
    {
        $app = new Illuminate\Foundation\Application();
        $app['redis'] = new RedisDatabase();
        $app['config'] = [
            'cache.prefix' => '',
            'cache.default' => 'redis',
            'cache.stores.redis' => [
                'driver' => 'ehann-redis',
            ],
        ];
        $cacheManager = new CacheManager($app);
        $cacheManager->extend('ehann-redis', function ($app) use ($cacheManager) {
            return $cacheManager->repository(new RedisStore($app['redis']));
        });
        $this->assertFalse($cacheManager->getUseCompression());
    }

    public function testRedisStoreCanBeConfiguredToUseCompression()
    {
        $app = new Illuminate\Foundation\Application();
        $app['redis'] = new RedisDatabase();
        $app['config'] = [
            'cache.prefix' => '',
            'cache.default' => 'redis',
            'cache.stores.redis' => [
                'driver' => 'ehann-redis',
                'use_compression' => true,
            ],
        ];
        $cacheManager = new CacheManager($app);
        $cacheManager->extend('ehann-redis', function ($app) use ($cacheManager) {
            $redisStore = new RedisStore($app['redis']);
            $redisStore->setUseCompression($app['config']['cache.stores.redis']['use_compression']);
            return $cacheManager->repository($redisStore);
        });
        $this->assertTrue($cacheManager->getUseCompression());
    }
}
