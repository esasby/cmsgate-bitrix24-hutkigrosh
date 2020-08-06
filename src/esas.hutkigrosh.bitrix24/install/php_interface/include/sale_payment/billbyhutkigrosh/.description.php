<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Localization\Loc;
use esas\cmsgate\hutkigrosh\RegistryHutkigroshBitrix24;

Loc::loadMessages(__FILE__);
require_once("init.php");

//наследуем параметры обработчика billby

require  \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/sale/handlers/paysystem/billby/.description.php';
$data['NAME'] = RegistryHutkigroshBitrix24::getRegistry()->getModuleDescriptor()->getModuleFullName();
$data['CODES'] = array_merge($data['CODES'], RegistryHutkigroshBitrix24::getRegistry()->createConfigForm()->generateCodes());
?>
