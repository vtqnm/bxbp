<?php

namespace Vtqnm\BxbpCli\Export;

use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractModuleExport implements ModuleExportStrategy
{
    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?? new Filesystem();
    }
}