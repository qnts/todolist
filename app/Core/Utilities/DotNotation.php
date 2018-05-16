<?php

namespace App\Core\Utilities;

class DotNotation
{
    /**
     * @var array the underlying data
     */
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @param $data
     * @return static
     */
    public static function create(&$data)
    {
        return new static($data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param $index
     * @return mixed|null
     */
    public function get($index)
    {
        $index = explode('.', $index);
        return $this->getValue($index, $this->data);
    }

    /**
     * Set a given key / value pair or pairs
     *
     * @param array|int|string $keys
     * @param mixed            $value
     */
    public function set($keys, $value = null)
    {
        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $this->set($key, $value);
            }
            return;
        }
        $items = &$this->data;
        foreach (explode('.', $keys) as $key) {
            if (!isset($items[$key]) || !is_array($items[$key])) {
                $items[$key] = [];
            }
            $items = &$items[$key];
        }
        $items = $value;
    }

    /**
     * Delete the given key or keys
     *
     * @param array|int|string $keys
     */
    public function delete($keys)
    {
        $keys = (array) $keys;
        foreach ($keys as $key) {
            if ($this->exists($this->data, $key)) {
                unset($this->data[$key]);
                continue;
            }
            $items = &$this->data;
            $segments = explode('.', $key);
            $lastSegment = array_pop($segments);
            foreach ($segments as $segment) {
                if (!isset($items[$segment]) || !is_array($items[$segment])) {
                    continue 2;
                }
                $items = &$items[$segment];
            }
            unset($items[$lastSegment]);
        }
    }

    /**
     * Check if a given key or keys exists
     *
     * @param  array|int|string $keys
     * @return bool
     */
    public function has($keys)
    {
        $keys = (array) $keys;
        if (!$this->data || $keys === []) {
            return false;
        }
        foreach ($keys as $key) {
            $items = $this->data;
            if ($this->exists($items, $key)) {
                continue;
            }
            foreach (explode('.', $key) as $segment) {
                if (!is_array($items) || !$this->exists($items, $segment)) {
                    return false;
                }
                $items = $items[$segment];
            }
        }
        return true;
    }

    /**
     * Checks if the given key exists in the provided array.
     *
     * @param  array      $array Array to validate
     * @param  int|string $key   The key to look for
     *
     * @return bool
     */
    protected function exists($array, $key)
    {
        return array_key_exists($key, $array);
    }

    /**
     * Navigate through a config array looking for a particular index
     * @param array $index The index sequence we are navigating down
     * @param array $value The portion of the config array to process
     * @return mixed|null
     */
    private function getValue($index, $value)
    {
        if (is_array($index) && count($index)) {
            $current_index = array_shift($index);
        }
        if (is_array($index) && count($index) && is_array($value[$current_index]) && count($value[$current_index])) {
            return self::getValue($index, $value[$current_index]);
        } else {
            return isset($value[$current_index]) ? $value[$current_index] : null;
        }
    }
}
