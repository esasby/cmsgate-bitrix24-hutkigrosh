<?php

namespace esas\cmsgate\hutkigrosh\controllers;

use CSaleOrder;

class ControllerHutkigroshNotifyBitrix24 extends ControllerHutkigroshNotify
{
    public function onStatusPayed()
    {
        parent::onStatusPayed();
        CSaleOrder::Update($this->localOrderWrapper->getOrderId(), array("PAYED" => "Y"));
        CSaleOrder::PayOrder($this->localOrderWrapper->getOrderId(), "Y");
    }

}