<?php
/**
 * @author Krzysztof Gzocha <krzysztof.gzocha@xsolve.pl>
 */

namespace Team3\Communication\Request;

use Team3\Order\Model\OrderInterface;

class OrderStatusRequest extends AbstractPayURequest
{
    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->data = $order;
        $this->path = sprintf('orders/%s', $order->getPayUOrderId());
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::METHOD_GET;
    }
}