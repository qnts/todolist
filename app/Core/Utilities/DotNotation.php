<?php

namespace App\Core\Utilities;

class DotNotation
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function get($index)
    {
        $index = explode('.', $index);
        return $this->getValue($index, $this->data);
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
            return $value[$current_index];
        }
    }
}
