<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 23.07.2020
 * Time: 12:45
 */

use esas\cmsgate\CmsConnectorBitrix24;
use esas\cmsgate\hutkigrosh\RegistryHutkigroshBitrix24;
use esas\cmsgate\hutkigrosh\view\client\ClientViewFieldsHutkigrosh;

/**
 * result_modifier используется для того, чтобы на странице публичной ссылки подменить логоти
 * и подпись для оплаты (вместо лого и текста hutkigrosh используются лого и текст webpay )
 */
$paySystemId = CmsConnectorBitrix24::getInstance()->getPaysystemId();
if ($paySystemId > 0) {
    foreach ($arResult['PAYSYSTEMS_LIST'] as $key => &$paySystem) {
        if ($paySystem['ID'] == $paySystemId) {
            $paySystem['NAME'] = RegistryHutkigroshBitrix24::getRegistry()->getTranslator()->translate(ClientViewFieldsHutkigrosh::WEBPAY_TAB_LABEL);
            $paySystem["LOGOTIP"] = '/bitrix/images/sale/sale_payments/hutkigrosh_webpay.png';
        }
    }
    unset($paySystem);
}