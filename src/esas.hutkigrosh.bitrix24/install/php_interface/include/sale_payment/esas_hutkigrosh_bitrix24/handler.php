<?

namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Request;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;
use esas\cmsgate\bitrix\CmsgateServiceHandler;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshNotifyBitrix24;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillInfoRs;
use esas\cmsgate\utils\CMSGateException;
use Exception;

/**
 * Отдельный handler для корректной работы callback-в.
 * Проблема связана с тем, что billbyhutkigrosh/handler наследуется от BillByHandler, а при колбэке в \Bitrix\Sale\PaySystem\Service::processRequest
 * проверяется, чтобы handler был наследником ServiceHandler.
 * Имя класса обязательно должно совпадать с именем родительского каталога и значением в ACTION_FILE в БД (\esas\cmsgate\bitrix\CmsgateCModule::addPaysys)
 * @package Sale\Handlers\PaySystem
 */
class esas_hutkigrosh_bitrix24Handler extends CmsgateServiceHandler
{
    /**
     * @param Payment $payment
     * @param Request|null $request
     * @return PaySystem\ServiceResult
     * @throws \Bitrix\Main\LoaderException
     */
    public function initiatePay(Payment $payment, Request $request = null)
    {
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CMSGateException
     */
    public function getPaymentIdFromRequestSafe(Request $request)
    {
        $controller = new ControllerHutkigroshNotifyBitrix24();
        $billInfoRs = $controller->process();
        CMSGateException::throwIfNull($billInfoRs, "Hutkigrosh bill info rs is null");
        $_SESSION["hutkigrosh_bill_info_rs"] = $billInfoRs; // для корректной работы processRequest

        $dbPayment = \Bitrix\Crm\Invoice\PaymentCollection::getList([
            'select' => ['ID'],
            'filter' => [
                '=ORDER_ID' => $billInfoRs->getInvId(),
            ]
        ]);
        while ($item = $dbPayment->fetch()) {
            return $item["ID"];
        }
        throw new CMSGateException("Can not find payments for order[" . $billInfoRs->getInvId() . "]");
    }

    /**
     * @param Payment $payment
     * @param Request $request
     * @return PaySystem\ServiceResult
     * @throws Exception
     */
    public function processRequestSafe(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();
        /** @var HutkigroshBillInfoRs $billInfoRs */
        $billInfoRs = $_SESSION["hutkigrosh_bill_info_rs"];
        CMSGateException::throwIfNull($billInfoRs, "Hutkigrosh bill is not loaded");
        $fields = array(
            "PS_STATUS" => $billInfoRs->isStatusPayed() ? "Y" : "N",
            "PS_STATUS_CODE" => $billInfoRs->getStatus(),
            "PS_STATUS_DESCRIPTION" => $billInfoRs->getResponseMessage(),
            "PS_STATUS_MESSAGE" => "",
            "PS_SUM" => $billInfoRs->getAmount()->getValue(),
            "PS_CURRENCY" => $billInfoRs->getAmount()->getCurrency(),
            "PS_RESPONSE_DATE" => new DateTime(),
        );
        $result->setPsData($fields);
        $result->setOperationType(PaySystem\ServiceResult::MONEY_COMING);
        return $result;
    }

    public function sendResponse(PaySystem\ServiceResult $result, Request $request)
    {
    }
}