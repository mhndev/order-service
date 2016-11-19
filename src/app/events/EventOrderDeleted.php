<?php
namespace mhndev\orderService\events;

use mhndev\order\interfaces\entities\iEntityOrder;

/**
 * Class EventOrderDeleted
 * @package mhndev\orderService\events
 */
class EventOrderDeleted
{

    /**
     * @param iEntityOrder $order
     */
    public function __invoke(iEntityOrder $order)
    {
        //todo call and endpoint to notify subscribers (e.x. admin ) order deletion
    }
}
