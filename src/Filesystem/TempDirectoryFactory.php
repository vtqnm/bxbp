<?php

namespace Vtqnm\Bxbp\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class TempDirectoryFactory
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();

        register_shutdown_function([TempDirectoryCleaner::class, 'run']);
    }

    public function create(): Directory
    {
        $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('bxbp_module_');

        $this->filesystem->mkdir($tempDir);
        TempDirectoryCleaner::register($tempDir);

        return new Directory($tempDir);
    }
}