<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Exceptions\InvalidModuleExportException;
use Vtqnm\BxbpCli\Export\ModuleExportFactory;
use Vtqnm\BxbpCli\Export\Strategy\RawModuleExport;

it('creates raw export strategy', function () {
    $exportStrategy = ModuleExportFactory::create('raw');

    expect($exportStrategy)->toBeInstanceOf(RawModuleExport::class);
});

it('throw exception for unknow export type', function () {
    expect(fn() => ModuleExportFactory::create('unknown'))
        ->toThrow(InvalidModuleExportException::class);
});