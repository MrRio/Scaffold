<?php defined('SCAFFOLD') or die();

/**
 * Autoload classes when they are requested.
 *
 * @author Nathaniel Higgins
 */
class Autoload {

    /**
     * Register with PHP's spl_autoload
     */
    public static function run() {
        spl_autoload_register(['Autoload', 'load']);
    }

    /**
     * Load a class.
     *
     * Typically used internally by spl_autoload, but
     * can be used manually.
     *
     * @param string $class Class name to load.
     * @return bool (true|false) Did a class get loaded
     */
    public static function load($class) {
        $parts = preg_split('/([[:upper:]][[:lower:]]+)/', $class, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

        for ($i = 1, $l = count($parts); $i < $l; $i++) {
            $part = $parts[$i];

            if (strtoupper($part) === $part) {
                $parts[$i -1] .= $part;
                unset($parts[$i]);
            }
        }

        $parts = array_map('strtolower', $parts);

        if (count($parts) > 1 && in_array($parts[0], ['controller', 'model', 'exception'])) {
            $parts[0] = $parts[0] . 's';
        }

        $path = strtolower(implode(DS, $parts)) . '.php';

        return load_file('classes' . DS . $path);
    }
}
