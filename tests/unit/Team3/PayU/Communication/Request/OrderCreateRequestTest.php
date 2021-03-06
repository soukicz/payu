<?php
namespace Team3\PayU\Communication\Request;

use Team3\PayU\Order\Model\Order;

class OrderCreateRequestTest extends \Codeception\TestCase\Test
{
    const DATA = 'data';
    const PATH = 'orders';

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testParameters()
    {
        $order = new Order();
        $request = new OrderCreateRequest($order);

        $this->assertInstanceOf(
            'Team3\PayU\Order\Model\OrderInterface',
            $request->getDataObject()
        );

        $this->assertEquals(
            self::PATH,
            $request->getPath()
        );

        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }
}
