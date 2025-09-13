<?php

namespace Vtqnm\BxbpCli\Export;

use Vtqnm\BxbpCli\Export\Strategy\RawModuleExport;
use Vtqnm\BxbpCli\Export\Strategy\TarModuleExport;
use Vtqnm\BxbpCli\Export\Strategy\ZipModuleExport;

class ModuleExportFactory
{
    public static function create(string $type): ModuleExportStrategy
    {
        return match ($type) {
            'tar' => new TarModuleExport(),
            'zip' => new ZipModuleExport(),
            'raw' => new RawModuleExport(),
            default => throw new \InvalidArgumentException("Unknown export type: {$type}")
        };
    }
}