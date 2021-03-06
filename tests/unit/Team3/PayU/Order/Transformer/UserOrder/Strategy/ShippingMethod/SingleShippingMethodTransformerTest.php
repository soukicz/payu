<?php
namespace Team3\PayU\Order\Transformer\UserOrder\Strategy\ShippingMethod;

use Team3\PayU\PropertyExtractor\Extractor;
use Team3\PayU\PropertyExtractor\ExtractorInterface;
use Team3\PayU\PropertyExtractor\Reader\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use tests\unit\Team3\PayU\Order\Transformer\UserOrder\Strategy\Model\UsersShippingModel;

/**
 * Class SingleShippingMethodTransformerTest
 * @package Team3\PayU\Order\Transformer\UserOrder\Strategy\ShippingMethod
 * @group money
 */
class SingleShippingMethodTransformerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var ExtractorInterface
     */
    protected $extractor;

    /**
     * @var SingleShippingMethodTransformer
     */
    protected $singleShippingMethodTransformer;

    protected function _before()
    {
        $this->extractor = new Extractor(
            new AnnotationReader(
                new DoctrineAnnotationReader(),
                $this->getLogger()
            ),
            $this->getLogger()
        );

        $this->singleShippingMethodTransformer = new SingleShippingMethodTransformer(
            $this->extractor
        );
    }

    public function testOnRealExample()
    {
        $shippingMethod = $this->singleShippingMethodTransformer->transform(
            new UsersShippingModel()
        );

        $this->assertInstanceOf(
            'Team3\PayU\Order\Model\ShippingMethods\ShippingMethodInterface',
            $shippingMethod
        );

        $this->assertNotEmpty($shippingMethod->getCountry());
        $this->assertNotEmpty($shippingMethod->getName());
        $this->assertNotEmpty($shippingMethod->getPrice());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    private function getLogger()
    {
        return $this->getMock('Psr\Log\LoggerInterface');
    }
}
