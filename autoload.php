<?php

class autoload
{
    private string $prefix;
    private string $dir;

    public function __construct(string $prefix, string $dir)
    {
        $this->prefix = $prefix . '\\';
        $this->dir = $dir . DIRECTORY_SEPARATOR;
    }

    public function loadClass(string $className)
    {
   
        $class = substr($className, strlen($this->prefix));

        $file = $this->dir . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }
}

?>