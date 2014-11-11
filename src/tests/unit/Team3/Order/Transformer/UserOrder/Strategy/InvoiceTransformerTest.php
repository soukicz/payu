<?php
namespace Team3\Order\Transformer\UserOrder\Strategy;

use Team3\Order\Annotation\PayU;
use Team3\Order\Model\Order;
use Team3\Order\Model\OrderInterface;
use Team3\Order\PropertyExtractor\Extractor;
use Team3\Order\PropertyExtractor\ExtractorInterface;
use Team3\Order\PropertyExtractor\ExtractorResult;
use Team3\Order\PropertyExtractor\Reader\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use tests\unit\Team3\Order\Transformer\UserOrder\Strategy\Model\InvoiceModelWithPrivateMethods;

class InvoiceTransformerTest extends \Codeception\TestCase\Test
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
     * @var InvoiceTransformer
     */
    protected $invoiceTransformer;

    protected function _before()
    {
        //autoload payu annotation
        new PayU();

        $this->extractor = new Extractor(
            new AnnotationReader(
                new DoctrineAnnotationReader()
            )
        );

        $this->invoiceTransformer = new InvoiceTransformer();
    }

    public function testIfImplementsStrategyInterface()
    {
        $this->assertInstanceOf(
            'Team3\Order\Transformer\UserOrder\Strategy\UserOrderTransformerStrategyInterface',
            $this->invoiceTransformer
        );
    }

    public function testIfAllValuesAreCopied()
    {
        $order = new Order();
        $invoiceModel = new InvoiceModelWithPrivateMethods();

        $this->copyAllValues($order, $invoiceModel);

        $invoice = $order->getBuyer()->getInvoice();
        $this->assertNotEmpty($invoice->getCity());
        $this->assertNotEmpty($invoice->getCountryCode());
        $this->assertNotEmpty($invoice->getName());
        $this->assertNotEmpty($invoice->getPostalCode());
        $this->assertNotEmpty($invoice->getRecipientEmail());
        $this->assertNotEmpty($invoice->getRecipientName());
        $this->assertNotEmpty($invoice->getRecipientPhone());
        $this->assertNotEmpty($invoice->getStreet());
        $this->assertNotEmpty($invoice->getTin());
        $this->assertNotEmpty($invoice->isEInvoiceRequested());
    }

    /**
     * @param OrderInterface                 $order
     * @param InvoiceModelWithPrivateMethods $invoice
     */
    private function copyAllValues(
        OrderInterface $order,
        InvoiceModelWithPrivateMethods $invoice
    ) {
        $results = $this
            ->extractor
            ->extract($invoice);

        /** @var ExtractorResult $result */
        foreach ($results as $result) {
            if ($this->invoiceTransformer->supports($result->getPropertyName())) {
                $this->invoiceTransformer->transform(
                    $order,
                    $result
                );
            }
        }
    }
}