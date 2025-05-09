<?php
/**
 * Copyright © 2019-2025 Rhubarb Tech Inc. All Rights Reserved.
 *
 * The Object Cache Pro Software and its related materials are property and confidential
 * information of Rhubarb Tech Inc. Any reproduction, use, distribution, or exploitation
 * of the Object Cache Pro Software and its related materials, in whole or in part,
 * is strictly forbidden unless prior permission is obtained from Rhubarb Tech Inc.
 *
 * In addition, any reproduction, use, distribution, or exploitation of the Object Cache Pro
 * Software and its related materials, in whole or in part, is subject to the End-User License
 * Agreement accessible in the included `LICENSE` file, or at: https://objectcache.pro/eula
 */

declare(strict_types=1);

namespace RedisCachePro\ObjectCaches;

use RedisCachePro\Configuration\Configuration;

class ArrayObjectCache extends ObjectCache
{
    /**
     * Create new array object cache instance.
     *
     * @param  \RedisCachePro\Configuration\Configuration  $config
     * @param  ?\RedisCachePro\ObjectCaches\ObjectCacheMetrics  $metrics
     */
    public function __construct(Configuration $config, ?ObjectCacheMetrics $metrics = null)
    {
        $this->setup($config, null, $metrics);
    }

    /**
     * Adds data to the cache, if the cache key doesn't already exist.
     *
     * @param  int|string  $key
     * @param  mixed  $data
     * @param  string  $group
     * @param  int  $expire
     * @return bool
     */
    public function add($key, $data, string $group = 'default', int $expire = 0): bool
    {
        if (function_exists('wp_suspend_cache_addition') && \wp_suspend_cache_addition()) {
            return false;
        }

        if ($this->has($key, $group)) {
            return false;
        }

        return $this->set($key, $data, $group, $expire);
    }

    /**
     * Adds multiple values to the cache in one call, if the cache keys doesn't already exist.
     *
     * @param  array<int|string, mixed>  $data
     * @param  string  $group
     * @param  int  $expire
     * @return array<int|string, bool>
     */
    public function add_multiple(array $data, string $group = 'default', int $expire = 0): array
    {
        $results = [];

        foreach ($data as $key => $value) {
            if ($this->id($key, $group)) {
                $results[$key] = $this->add($key, $value, $group, $expire);
            }
        }

        return $results;
    }

    /**
     * Boots the cache.
     *
     * @return bool
     */
    public function boot(): bool
    {
        return true;
    }

    /**
     * Closes the cache.
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Decrements numeric cache item's value.
     *
     * @param  int|string  $key
     * @param  int  $offset
     * @param  string  $group
     * @return int|false
     */
    public function decr($key, int $offset = 1, string $group = 'default')
    {
        if (! $this->has($key, $group)) {
            return false;
        }

        if (! $id = $this->id($key, $group)) {
            return false;
        }

        $value = $this->cache[$group][$id];
        $value = $this->decrement($value, $offset);

        $this->cache[$group][$id] = $value;

        return $value;
    }

    /**
     * Removes the cache contents matching key and group.
     *
     * @param  int|string  $key
     * @param  string  $group
     * @return bool
     */
    public function delete($key, string $group = 'default'): bool
    {
        if (! $this->has($key, $group)) {
            return false;
        }

        if (! $id = $this->id($key, $group)) {
            return false;
        }

        unset($this->cache[$group][$id]);

        return true;
    }

    /**
     * Deletes multiple values from the cache in one call.
     *
     * @param  array<int|string>  $keys
     * @param  string  $group
     * @return array<int|string, bool>
     */
    public function delete_multiple(array $keys, string $group = 'default'): array
    {
        $results = [];

        foreach ($keys as $key) {
            if ($this->id($key, $group)) {
                $results[$key] = $this->delete($key, $group);
            }
        }

        return $results;
    }

    /**
     * Removes all cache items.
     *
     * @return bool
     */
    public function flush(): bool
    {
        $this->cache = [];

        return true;
    }

    /**
     * Removes all cache items from the in-memory runtime cache.
     *
     * @return bool
     */
    public function flush_runtime(): bool
    {
        return $this->flush();
    }

    /**
     * Removes all cache items in given group.
     *
     * @param  string  $group
     * @return bool
     */
    public function flush_group(string $group): bool
    {
        unset($this->cache[$group]);

        return true;
    }

    /**
     * Retrieves the cache contents from the cache by key and group.
     *
     * @param  int|string  $key
     * @param  string  $group
     * @param  bool  $force
     * @param  bool  &$found
     * @return bool|mixed
     */
    public function get($key, string $group = 'default', bool $force = false, &$found = false)
    {
        if (! $this->has($key, $group)) {
            $found = false;
            $this->metrics->misses += 1;

            return false;
        }

        $found = true;
        $this->metrics->hits += 1;

        $id = $this->id($key, $group);

        if (\is_object($this->cache[$group][$id])) {
            return clone $this->cache[$group][$id];
        }

        return $this->cache[$group][$id];
    }

    /**
     * Retrieves multiple values from the cache in one call.
     *
     * @param  array<int|string>  $keys
     * @param  string  $group
     * @param  bool  $force
     * @return array<int|string, mixed>
     */
    public function get_multiple(array $keys, string $group = 'default', bool $force = false)
    {
        $values = [];

        foreach ($keys as $key) {
            if ($this->id($key, $group)) {
                $values[$key] = $this->get($key, $group, $force);
            }
        }

        return $values;
    }

    /**
     * Whether the key exists in the cache.
     *
     * @param  int|string  $key
     * @param  string  $group
     * @return bool
     */
    public function has($key, string $group = 'default'): bool
    {
        $id = $this->id($key, $group);

        return isset($this->cache[$group][$id]);
    }

    /**
     * Increment numeric cache item's value.
     *
     * @param  int|string  $key
     * @param  int  $offset
     * @param  string  $group
     * @return int|false
     */
    public function incr($key, int $offset = 1, string $group = 'default')
    {
        if (! $this->has($key, $group)) {
            return false;
        }

        if (! $id = $this->id($key, $group)) {
            return false;
        }

        $value = $this->cache[$group][$id];
        $value = $this->increment($value, $offset);

        $this->cache[$group][$id] = $value;

        return $value;
    }

    /**
     * Replaces the contents of the cache with new data.
     *
     * @param  int|string  $key
     * @param  mixed  $data
     * @param  string  $group
     * @param  int  $expire
     * @return bool
     */
    public function replace($key, $data, string $group = 'default', int $expire = 0): bool
    {
        if (! $this->has($key, $group)) {
            return false;
        }

        return $this->set($key, $data, $group, $expire);
    }

    /**
     * Saves the data to the cache.
     *
     * @param  int|string  $key
     * @param  mixed  $data
     * @param  string  $group
     * @param  int  $expire
     * @return bool
     */
    public function set($key, $data, string $group = 'default', int $expire = 0): bool
    {
        if (\is_object($data)) {
            $data = clone $data;
        }

        if (! $id = $this->id($key, $group)) {
            return false;
        }

        $this->cache[$group][$id] = $data;

        return true;
    }

    /**
     * Sets multiple values to the cache in one call.
     *
     * @param  array<int|string, mixed>  $data
     * @param  string  $group
     * @param  int  $expire
     * @return array<int|string, bool>
     */
    public function set_multiple(array $data, string $group = 'default', int $expire = 0): array
    {
        $results = [];

        foreach ($data as $key => $value) {
            if ($this->id($key, $group)) {
                $results[$key] = $this->set($key, $value, $group, $expire);
            }
        }

        return $results;
    }

    /**
     * Switches the internal blog ID.
     *
     * @param  int $blog_id
     * @return bool
     */
    public function switch_to_blog(int $blog_id): bool
    {
        if ($this->isMultisite) {
            $this->setBlogId($blog_id);

            return true;
        }

        return false;
    }
}
