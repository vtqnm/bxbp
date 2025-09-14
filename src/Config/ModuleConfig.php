<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Config;

class ModuleConfig
{
    protected string $moduleId;
    protected string $moduleName;
    protected string $moduleDescription;
    protected string $partnerName;
    protected string $partnerUri;
    protected string $version;
    protected string $versionDate;
    protected string $languageCode;

    /**
     * @param string $moduleId
     * @param string $moduleName
     * @param string $moduleDescription
     * @param string $partnerName
     * @param string $partnerUri
     * @param string $version
     * @param string $versionDate
     * @param string $languageCode
     */
    public function __construct(
        string $moduleId, 
        string $moduleName, 
        string $moduleDescription, 
        string $partnerName, 
        string $partnerUri, 
        string $version, 
        string $versionDate, 
        string $languageCode
    )
    {
        $this->moduleId = $moduleId;
        $this->moduleName = $moduleName;
        $this->moduleDescription = $moduleDescription;
        $this->partnerName = $partnerName;
        $this->partnerUri = $partnerUri;
        $this->version = $version;
        $this->versionDate = $versionDate;
        $this->languageCode = $languageCode;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function getClassName(): string
    {
        return str_replace('.', '_', $this->moduleId);
    }

    public function getLangModulePrefix(): string
    {
        return str_replace('.', '_', mb_strtoupper($this->moduleId));
    }

    public function toReplacementMap(): array
    {
        return [
            '{CLASS_NAME}' => $this->getClassName(),
            '{MODULE_ID}' => $this->moduleId,
            '{MODULE_NAME}' => $this->moduleName,
            '{MODULE_DESCRIPTION}' => $this->moduleDescription,
            '{PARTNER_NAME}' => $this->partnerName,
            '{PARTNER_URI}' => $this->partnerUri,
            '{VERSION}' => $this->version,
            '{VERSION_DATE}' => $this->versionDate,
            '{LANG_MODULE_PREFIX}' => $this->getLangModulePrefix()
        ];
    }

}