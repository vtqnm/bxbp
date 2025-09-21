<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class {CLASS_NAME} extends CModule {
    var $MODULE_ID = '{MODULE_ID}';
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . '/version.php');
        if (!empty($arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('{LANG_MODULE_PREFIX}_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('{LANG_MODULE_PREFIX}_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('{LANG_MODULE_PREFIX}_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('{LANG_MODULE_PREFIX}_PARTNER_URI');
    }

    public function DoInstall()
    {
        ModuleManager::registerModule('{MODULE_ID}');

        $this->InstallDB();
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();

        ModuleManager::unRegisterModule('{MODULE_ID}');
    }

    public function InstallDB()
    {
    }

    public function UnInstallDB()
    {
    }
}