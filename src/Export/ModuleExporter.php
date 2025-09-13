<?php

namespace Vtqnm\BxbpCli\Export;

use Vtqnm\BxbpCli\Filesystem\Directory;

class ModuleExporter
{
    private ModuleExportStrategy $exportStrategy;

    public function __construct(ModuleExportStrategy $exportStrategy)
    {
        $this->exportStrategy = $exportStrategy;
    }

    public function export(Directory $source, string $destination)
    {
        $this->exportStrategy->export($source, $destination);
    }
}