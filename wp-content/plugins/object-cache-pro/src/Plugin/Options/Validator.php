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

namespace RedisCachePro\Plugin\Options;

use WP_Error;
use RedisCachePro\Plugin;

class Validator
{
    /**
     * The plugin instance.
     *
     * @var \RedisCachePro\Plugin
     */
    private $plugin;

    /**
     * Creates a new instance.
     *
     * @param  \RedisCachePro\Plugin  $plugin
     * @return void
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Validates all given options.
     *
     * @param  array<string, mixed>  $options
     * @return true|\WP_Error
     */
    public function validate(array $options)
    {
        $errors = new WP_Error();

        foreach ($options as $name => $value) {
            if (is_wp_error($error = $this->{$name}($value))) {
                $errors->merge_from($error);
            }
        }

        if ($errors->has_errors()) {
            $errors->add_data(['status' => 422]);

            return $errors;
        }

        return true;
    }

    /**
     * Returns a `WP_Error` instance for the given code and message.
     *
     * @param  string  $code
     * @param  string  $message
     * @return \WP_Error
     */
    protected function error($code, $message)
    {
        return new WP_Error($code, $message);
    }

    /**
     * Validate the `channel` option value.
     *
     * @param  string  $value
     * @return void|\WP_Error
     */
    public function channel($value)
    {
        $license = $this->plugin->license();

        $stabilities = $license::Stabilities;
        $accessibleStabilities = $license->accessibleStabilities();

        if (! in_array($value, array_keys($stabilities), true)) {
            return $this->error('invalid-channel', sprintf('The channel "%s" is not valid.', $value));
        }

        if (! in_array($value, array_keys($accessibleStabilities), true)) {
            return $this->error('inaccessible-channel', sprintf('Your license cannot use the "%s" update channel.', $value));
        }
    }

    /**
     * Validate the `flushlog` option value.
     *
     * @param  bool  $value
     * @return void|\WP_Error
     */
    public function flushlog($value)
    {
        if (! is_bool($value)) {
            return $this->error('invalid-type', 'Option must be boolean.');
        }
    }

    /**
     * Validate the `groupflushlog` option value.
     *
     * @param  bool  $value
     * @return void|\WP_Error
     */
    public function groupflushlog($value)
    {
        if (! is_bool($value)) {
            return $this->error('invalid-type', 'Option must be boolean.');
        }
    }
}
