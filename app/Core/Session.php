<?php

namespace App\Core;

class Session
{
    public function set($key, $value = '')
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $this->has($key) ? $_SESSION[$key] : null;
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function setFlash($key, $value = '')
    {
        $this->set('f__' . $key, $value);
    }

    public function getFlash($key)
    {
        return $this->get('f__' . $key);
    }

    public function hasFlash($key)
    {
        return $this->has('f__' . $key);
    }

    public function flash($key)
    {
        if ($this->hasFlash($key)) {
            $data = $this->getFlash($key);
            $this->delete('f__' . $key);
            return $data;
        }
        return;
    }
}
