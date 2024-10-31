<?php
/**
 * Utility functions used in the plugin
 */
namespace Poiju;

// Do not allow direct access
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Get a value from an array, or a default value if the key doesn't exist
 *
 * @param mixed[] $arr The array to search
 * @param mixed $key The key to look for
 * @param mixed $default The value returned if the key is not in the array
 *
 * @return mixed
 */
function value_or_default($arr, $key, $default) {
    if (array_key_exists($key, $arr)) {
        return $arr[$key];
    }
    return $default;
}
