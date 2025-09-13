<?php

namespace Vtqnm\BxbpCli\Export;

use Vtqnm\BxbpCli\Filesystem\Directory;

interface ModuleExportStrategy
{
    public function export(Directory $source, string $destination): string;
}