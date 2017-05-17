<?php

namespace Laravel\ProductFields;

use Illuminate\Support\Arr;

class ResourcesResolver
{
    /**
     * @var array
     */
    protected static $resolvers = [];

    /**
     * @param string   $model
     * @param string   $shortKey
     * @param callable $resolver
     */
    public static function registerResolver(string $model, string $shortKey, callable $resolver)
    {
        static::$resolvers[$model] = [
            'short_key' => $shortKey,
            'resolver' => $resolver,
        ];
    }

    /**
     * @param string $resource
     * @param array  $ids
     *
     * @return mixed
     */
    public function resolve(string $resource, array $ids)
    {
        $shortKey = Arr::get(static::$resolvers, $resource.'.short_key', $resource);
        $resolver = Arr::get(static::$resolvers, $resource.'.resolver');

        if (!is_callable($resolver)) {
            throw new \InvalidArgumentException('Resolver for given resource ('.$resource.') was not found.');
        }

        return [
            'short_key' => $shortKey,
            'resources' => call_user_func_array($resolver, [$ids]),
        ];
    }
}
