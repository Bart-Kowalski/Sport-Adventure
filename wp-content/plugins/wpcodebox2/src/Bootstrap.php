<?php

namespace Wpcb2;



class Bootstrap
{

    function autoload($className) {

        if(strpos($className, '\\') !== false){

            $prefix = 'Wpcb2\\';
            $base_dir = str_replace('\\', '/', dirname(__FILE__)) . '/';

            // does the class use the namespace prefix?
            $len = strlen($prefix);
            if (strncmp($prefix, $className, $len) !== 0) {
                // no, move to the next registered autoloader
                return;
            }

            // get the relative class name
            $relative_class = substr($className, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) {
                require_once $file;
            }
        }

        return;
    }

}
