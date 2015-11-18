<?php

namespace Ozag\Skeleton\Infrastructure\Config\Repository;

class ConfigRepository
{
    /**
     * @var array
     */
    private $values;

    /**
     * ConfigRepository constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->values = [];

        $files = glob($path . '/*.php');
        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $this->set($filename, require_once $file);
        }
    }

    /**
     * Get configuration item.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->values[$key];
    }

    /**
     * Set configuration item.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * Check if configuration item exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->values[$key]);
    }
}
