<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Config;

use Vtqnm\BxbpCli\Exceptions\ModuleValidationException;

class ModuleConfigValidator
{
    public function validateModuleId(string $moduleId): void
    {   
        if (empty(trim($moduleId))) {
            throw new ModuleValidationException('Module ID is required');
        }

        if (!preg_match('/^[a-z0-9.]+$/', $moduleId)) {
            throw new ModuleValidationException('Module ID must contain only lowercase letters and numbers');
        }

        if (!preg_match('/^\d/', $moduleId)) {
            throw new ModuleValidationException('Module ID not starts with a digit');
        }

        if (!preg_match('/^[a-z0-9]\.[a-z0-9]+$/', $moduleId)) {
            throw new ModuleValidationException('Module ID must be in format <partner_name>.<module_name>');
        }

        if (mb_strlen($moduleId) > 50) {
            throw new ModuleValidationException('Module ID must not exceed 50 characters');
        }
    }

    public function validateModuleName(string $moduleName): void
    {
        if (strlen($moduleName) > 255) {
            throw new ModuleValidationException('Module name must not exceed 255 characters');
        }
    }

    public function validateModuleDescription(string $moduleDescription): void
    {
        if (strlen($moduleDescription) > 1000) {
            throw new ModuleValidationException('Module description must not exceed 1000 characters');
        }
    }

    public function validatePartnerName(string $partnerName): void
    {
        if (strlen($partnerName) > 255) {
            throw new ModuleValidationException('Partner name must not exceed 255 characters');
        }
    }

    public function validatePartnerUri(string $partnerUri): void
    {
        if (strlen($partnerUri) > 255) {
            throw new ModuleValidationException('Partner URI must not exceed 255 characters');
        }
    }

    public function validateVersion(string $version): void
    {
        if (empty(trim($version))) {
            throw new ModuleValidationException('Version is required');
        }

        if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            throw new ModuleValidationException('Version must be in format X.Y.Z (e.g., 24.0.100)');
        }
    }

    public function validateVersionDate(string $versionDate): void
    {
        if (empty(trim($versionDate))) {
            throw new ModuleValidationException('Version date is required');
        }

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $versionDate);
        if (!$date || $date->format('Y-m-d H:i:s') !== $versionDate) {
            throw new ModuleValidationException('Version date must be in format YYYY-MM-DD HH:mm:ss');
        }
    }

    public function validateModuleLangCode(string $moduleLangCode): void
    {
        if (empty(trim($moduleLangCode))) {
            throw new ModuleValidationException('Module language code is required');
        }

        if (!preg_match('/^[a-z]{2}$/', $moduleLangCode)) {
            throw new ModuleValidationException('Module language code must contain only lowercase letters and consist of exactly 2 characters');
        }
    }
}
