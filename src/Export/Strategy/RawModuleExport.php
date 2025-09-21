<?php

namespace Vtqnm\Bxbp\Export\Strategy;

use Vtqnm\Bxbp\Export\AbstractModuleExport;
use Vtqnm\Bxbp\Filesystem\Directory;

class RawModuleExport extends AbstractModuleExport
{
    public function export(Directory $source, string $destination): string
    {
        $this->filesystem->mirror($source->getPath(), $destination);
        return $destination;
    }
}