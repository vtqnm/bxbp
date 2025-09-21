<?php

namespace Vtqnm\Bxbp\Export;

use Vtqnm\Bxbp\Filesystem\Directory;

class ModuleExporter
{
    private ModuleExportStrategy $exportStrategy;

    public function __construct(ModuleExportStrategy $exportStrategy)
    {
        $this->exportStrategy = $exportStrategy;
    }

    public function export(Directory $source, string $destination): void
    {
        $this->exportStrategy->export($source, $destination);
    }
}