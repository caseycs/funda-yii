<?php
class CacheMock implements IApplicationComponent
{
    public $memCache;

    public $data = array();
    public $expire = array();

    public function __construct()
    {
        $this->memCache = $this;
    }

    public function init()
    {
    }

    public function getIsInitialized()
    {
        return true;
    }

    public function set($k, $v, $expire = 0)
    {
        $this->data[$k] = $v;
        $this->expire[$k] = $expire;
    }

    public function get($k)
    {
        return isset($this->data[$k]) ? $this->data[$k] : false;
    }

    public function increment($k, $step = 1)
    {
        if (isset($this->data[$k])) {
            $this->data[$k] - $step;
            return $this->data[$k];
        } else {
            return false;
        }
    }
}
