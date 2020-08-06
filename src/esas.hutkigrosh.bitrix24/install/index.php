<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/esas.hutkigrosh.bitrix24/install/php_interface/include/sale_payment/billbyhutkigrosh/init.php");

use esas\cmsgate\bitrix\CmsgateCModuleBitrix24;
use esas\cmsgate\Registry;

if (class_exists('esas_hutkigrosh_bitrix24')) return;

class esas_hutkigrosh_bitrix24 extends CmsgateCModuleBitrix24
{

    public function getModuleActionName() {
        return "billbyhutkigrosh"; // обязательно должен начинаться с bill (см. bitrix/modules/crm/classes/general/crm_pay_system.php:1763)
    }

    protected function addFilesToInstallList()
    {
        parent::addFilesToInstallList();
        $this->installFilesList[] = "/components/bitrix/crm.invoice.payment.client/templates/.default/result_modifier.php";
        $this->installFilesList[] = "/php_interface/include/sale_payment/esas_hutkigrosh_bitrix24";
        $this->installFilesList[] = "/tools/sale_ps_hutkigrosh_result.php";
        $this->installFilesList[] = "/images/sale/sale_payments/hutkigrosh_webpay.png";
    }
}
