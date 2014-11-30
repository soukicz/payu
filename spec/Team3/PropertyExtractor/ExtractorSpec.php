<?php

namespace spec\Team3\PropertyExtractor;

use PhpSpec\ObjectBehavior;
use Team3\PropertyExtractor\Extractor;
use Team3\PropertyExtractor\Reader\ReaderInterface;

/**
 * Class ExtractorSpec
 * @package spec\Team3\Order\Annotation\Extractor
 * @mixin Extractor
 */
class ExtractorSpec extends ObjectBehavior
{
    public function let(ReaderInterface $reader)
    {
        $this->beConstructedWith($reader);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Team3\PropertyExtractor\Extractor');
    }

    public function it_should_call_reader(
        ReaderInterface $reader
    ) {
        $this->beConstructedWith($reader);
        $model = new \stdClass();
        $reader->read($model)->willReturn([]);
        $reader->read($model)->shouldBeCalled();
        $this->extract($model);
    }

    public function it_check_if_argument_is_an_object()
    {
        $this
            ->exceptionTest(null)
            ->exceptionTest(false)
            ->exceptionTest(2)
            ->exceptionTest(2.4)
            ->exceptionTest([]);
    }

    /**
     * @param mixed $variable
     *
     * @return $this
     */
    protected function exceptionTest($variable)
    {
        $this
            ->shouldThrow('Team3\\PropertyExtractor\\ExtractorException')
            ->during('extract', [$variable]);

        return $this;
    }
}