<?php

use Mockery as m;

class CacheRedisStoreTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testPutWhenCompressionIsEnabled()
    {
        $redis = $this->getRedis();
        $redis->setUseCompression(true);
        $value = 'foo';
        $redis->getRedis()->shouldReceive('connection')->once()->with('default')->andReturn($redis->getRedis());
        $redis->getRedis()->shouldReceive('setex')->once()->with('prefix:foo', 60 * 60, gzcompress(serialize($value)));
        $redis->put('foo', $value, 60);
    }

    public function testGetWhenCompressionIsEnabled()
    {
        $redis = $this->getRedis();
        $redis->setUseCompression(true);
        $value = 'foo';
        $redis->getRedis()->shouldReceive('connection')->once()->with('default')->andReturn($redis->getRedis());
        $redis->getRedis()->shouldReceive('get')->once()->with('prefix:foo')->andReturn(gzcompress(serialize($value)));
        $redis->get('foo');
    }

    public function testPutWhenCompressionIsDisabled()
    {
        $redis = $this->getRedis();
        $redis->setUseCompression(false);
        $value = 'foo';
        $redis->getRedis()->shouldReceive('connection')->once()->with('default')->andReturn($redis->getRedis());
        $redis->getRedis()->shouldReceive('setex')->once()->with('prefix:foo', 60 * 60, serialize($value));
        $redis->put('foo', $value, 60);
    }

    public function testGetWhenCompressionIsDisabled()
    {
        $redis = $this->getRedis();
        $redis->setUseCompression(false);
        $value = 'foo';
        $redis->getRedis()->shouldReceive('connection')->once()->with('default')->andReturn($redis->getRedis());
        $redis->getRedis()->shouldReceive('get')->once()->with('prefix:foo')->andReturn(serialize($value));
        $redis->get('foo');
    }

    protected function getRedis()
    {
        return new Ehann\Cache\RedisStore(m::mock('Illuminate\Redis\Database'), 'prefix');
    }
}
