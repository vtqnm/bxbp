<?php

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
    protected string $langPrefix;

    /**
     * @param string $moduleId
     * @param string $moduleName
     * @param string $moduleDescription
     * @param string $partnerName
     * @param string $partnerUri
     * @param string $version
     * @param string $versionDate
     * @param string $langPrefix
     */
    public function __construct(string $moduleId, string $moduleName, string $moduleDescription, string $partnerName, string $partnerUri, string $version, string $versionDate, string $langPrefix)
    {
        $this->moduleId = $moduleId;
        $this->moduleName = $moduleName;
        $this->moduleDescription = $moduleDescription;
        $this->partnerName = $partnerName;
        $this->partnerUri = $partnerUri;
        $this->version = $version;
        $this->versionDate = $versionDate;
        $this->langPrefix = $langPrefix;
    }

    public function getModuleId(): string
    {
        return $this->moduleId;
    }

    public function toReplacementMap(): array
    {
        return [
            '{CLASS_NAME}' => str_replace('.', '_', $this->moduleId),
            '{MODULE_ID}' => $this->moduleId,
            '{MODULE_NAME}' => $this->moduleName,
            '{MODULE_DESCRIPTION}' => $this->moduleDescription,
            '{PARTNER_NAME}' => $this->partnerName,
            '{PARTNER_URI}' => $this->partnerUri,
            '{VERSION}' => $this->version,
            '{VERSION_DATE}' => $this->versionDate,
            '{LANG_MODULE_PREFIX}' => $this->langPrefix
        ];
    }
}