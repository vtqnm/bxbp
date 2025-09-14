<?php

namespace Vtqnm\BxbpCli\Export;

use Vtqnm\BxbpCli\Exceptions\InvalidModuleExportException;
use Vtqnm\BxbpCli\Export\Strategy\RawModuleExport;

class ModuleExportFactory
{
    public static function create(string $type): ModuleExportStrategy
    {
        return match ($type) {
            // TODO zip and tar in future   
            // 'tar' => new TarModuleExport(),
            // 'zip' => new ZipModuleExport(),
            'raw' => new RawModuleExport(),
            default => throw new InvalidModuleExportException("Unknown export type: {$type}")
        };
    }
}