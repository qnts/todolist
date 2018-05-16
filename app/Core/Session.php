<?php

namespace App\Core;

class Session
{
    /**
     * Set a session
     * @param $key
     * @param string $value
     */
    public function set($key, $value = '')
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session
     * @param $key
     * @return null
     */
    public function get($key)
    {
        return $this->has($key) ? $_SESSION[$key] : null;
    }

    /**
     * Delete a session
     * @param $key
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Check if a session key exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Set a one time session
     * @param $key
     * @param string $value
     */
    public function setFlash($key, $value = '')
    {
        $this->set('f__' . $key, $value);
    }

    /**
     * Get a one time session value without deleting it
     * @param $key
     * @return null
     */
    public function getFlash($key)
    {
        return $this->get('f__' . $key);
    }

    /**
     * Check if a one time session exists
     * @param $key
     * @return bool
     */
    public function hasFlash($key)
    {
        return $this->has('f__' . $key);
    }

    /**
     * Retrieve a one time session value and then destroy it.
     * @param $key
     * @return mixed return null if session key not exists
     */
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
