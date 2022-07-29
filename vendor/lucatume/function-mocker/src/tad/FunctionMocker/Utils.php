<?php

namespace tad\FunctionMocker;


class Utils
{

    public static function filterPathListFrom(array $list, $rootDir)
    {
        \Arg::_($rootDir, 'Root dir')->assert(is_dir($rootDir), 'Root dir must be an existing dir');

        $_list = array_map(function ($frag) use ($rootDir) {
            $path = $rootDir . DIRECTORY_SEPARATOR . self::normalizePathFrag($frag);

            return file_exists($path) ? $path : null;
        }, $list);

        return array_filter($_list);
    }

    public static function normalizePathFrag($path)
    {
        \Arg::_($path, 'Path')->is_string();

        return trim(trim($path), '/');
    }

    public static function includePatchwork()
    {
       require_once Utils::getVendorDir('antecedent/patchwork/Patchwork.php');
    }

    public static function findParentContainingFrom($children, $cwd)
    {
        $dir = $cwd;
        $children = '/' . self::normalizePathFrag($children);
        while (true) {
            if (file_exists($dir . $children)) {
                break;
            } else {
                $dir = dirname($dir);
            }
        }

        return $dir;
    }

    /**
     * Gets the absolute path to the `vendor` dir optionally appending a path.
     *
     * @param string $path The relative path with no leading slash.
     *
     * @return string The absolute path to the file.
     */
    public static function getVendorDir($path = '')
    {
        $ref = new \ReflectionClass('Composer\Autoload\ClassLoader');
        $file = $ref->getFileName();

        $vendorDir = dirname(dirname($file));

        return empty($path) ? $vendorDir : $vendorDir . DIRECTORY_SEPARATOR . $path;
    }
}

