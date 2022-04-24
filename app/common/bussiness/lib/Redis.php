<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 10:48
 * File: Redis.php
 */

namespace app\common\bussiness\lib;

use think\cache\driver\Redis as R;
use think\facade\Cache;


class Redis
{
    private $store = null;
    private $redis = null;

    public function __construct($store = 'redis')
    {
        $this->setStore($store);
        $this->redis = new R(config('cache.stores.' . $this->store));
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function set($key, $value, $ttl = null)
    {
        return Cache::store($this->store)->set($key, $value, $ttl);
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get($key)
    {
        return Cache::store($this->store)->get($key);
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function delete($key)
    {
        return Cache::store($this->store)->delete($key);
    }

    public function setStore($store)
    {
        $this->store = $store;
        return $this;
    }

    public function rset($key, $value, $ttl = null)
    {
        return $this->redis->set($key, $value, $ttl);
    }

    public function multi()
    {
        return $this->redis->multi();
    }

    public function exec()
    {
        return $this->redis->exec();
    }

    public function discard()
    {
        return $this->redis->discard();
    }

}