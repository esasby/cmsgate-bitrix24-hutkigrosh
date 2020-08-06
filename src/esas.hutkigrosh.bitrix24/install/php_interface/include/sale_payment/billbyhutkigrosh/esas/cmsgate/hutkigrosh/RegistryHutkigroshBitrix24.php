<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\hutkigrosh;

use CCrmInvoice;
use esas\cmsgate\CmsConnectorBitrix24;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigroshBitrix24;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\view\admin\AdminViewFields;
use CMain;
use COption;
use esas\cmsgate\view\admin\ConfigFormBitrix;

class RegistryHutkigroshBitrix24 extends RegistryHutkigrosh
{
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorBitrix24();
        $this->paysystemConnector = new PaysystemConnectorHutkigrosh();
    }


    /**
     * Переопделение для упрощения типизации
     * @return RegistryHutkigroshBitrix24
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * @throws \Exception
     */
    public function createConfigForm()
    {
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON,
            [
                ConfigFieldsHutkigrosh::shopName(),
                ConfigFieldsHutkigrosh::paymentMethodName(),
                ConfigFieldsHutkigrosh::paymentMethodDetails(),
                ConfigFieldsHutkigrosh::completionCssFile(),
                ConfigFieldsHutkigrosh::webpaySection()
            ]);
        $configForm = new ConfigFormBitrix(
            AdminViewFields::CONFIG_FORM_COMMON,
            $managedFields);
        return $configForm;
    }

    function getUrlWebpay($orderId)
    {
        return CCrmInvoice::getPublicLink($orderId);
    }

    /**
     * @param $orderWrapper
     * @return CompletionPanelHutkigroshBitrix24
     */
    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelHutkigroshBitrix24($orderWrapper);
        return $completionPanel;
    }


    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "esas.hutkigrosh.bitrix24",
            new VersionDescriptor("1.10.0", "2020-06-17"),
            "Прием платежей через ЕРИП (сервис EPOS)",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-bitrix24-epos/browse",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }

    function getUrlAlfaclick($orderId)
    {
        // TODO: Implement getUrlAlfaclick() method.
    }
}