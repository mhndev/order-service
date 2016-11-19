<?php

namespace mhndev\orderService\events;

use mhndev\order\interfaces\entities\iEntityOrder;

/**
 * Class EventOrderCreated
 * @package mhndev\orderService\events
 */
class EventOrderCreated
{

    /**
     * @param iEntityOrder $order
     */
    public function __invoke(iEntityOrder $order)
    {
        //todo call and endpoint to notify subscribers (e.x. admin ) new order registration
    }
}
