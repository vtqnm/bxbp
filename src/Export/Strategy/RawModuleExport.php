<?php

namespace Vtqnm\BxbpCli\Export\Strategy;

use Vtqnm\BxbpCli\Export\AbstractModuleExport;
use Vtqnm\BxbpCli\Filesystem\Directory;

class RawModuleExport extends AbstractModuleExport
{
    public function export(Directory $source, string $destination): string
    {
        $this->filesystem->mirror($source->getPath(), $destination);
        return $destination;
    }
}