<?php

namespace Vtqnm\Bxbp\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class TempDirectoryCleaner
{
    /** @var array<string> */
    protected static array $dirs = [];

    public static function register(string $path): void
    {
        if (!in_array($path, static::$dirs)) {
            static::$dirs[] = $path;
        }
    }

    public static function run(): void
    {
        if (empty(static::$dirs)) {
            return;
        }

        $filesystem = new Filesystem();

        foreach (static::$dirs as $key => $folder) {
            if ($filesystem->exists($folder)) {
                $filesystem->remove($folder);
            }

            unset(static::$dirs[$key]);
        }
    }
}