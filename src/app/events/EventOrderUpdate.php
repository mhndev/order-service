<?php
namespace mhndev\orderService\events;

use mhndev\order\interfaces\entities\iEntityOrder;

/**
 * Class EventOrderUpdated
 * @package mhndev\orderService\events
 */
class EventOrderUpdated
{

    /**
     * @param iEntityOrder $order
     */
    public function __invoke(iEntityOrder $order)
    {
        //todo call and endpoint to notify subscribers (e.x. admin ) an order updated.
    }
}
