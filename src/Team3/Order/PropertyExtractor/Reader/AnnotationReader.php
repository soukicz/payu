<?php
/**
 * @author Krzysztof Gzocha <krzysztof.gzocha@xsolve.pl>
 */

namespace Team3\Order\PropertyExtractor\Reader;

use Doctrine\Common\Annotations\Reader;
use \ReflectionClass;
use \ReflectionMethod;

use Team3\Order\Annotation\PayU;

class AnnotationReader implements ReaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @inheritdoc
     */
    public function read($object)
    {
        $reflectionClass = new ReflectionClass($object);
        $read = [];

        foreach ($this->getMethods($reflectionClass) as $reflectionMethod) {
            /** @var PayU $methodAnnotation */
            $methodAnnotation = $this
                ->reader
                ->getMethodAnnotation($reflectionMethod, PayU::class);

            if (is_object($methodAnnotation)) {
                $read[] = new ReaderResult(
                    $reflectionMethod->getName(),
                    $methodAnnotation->getPropertyName()
                );
            }
        }

        return $read;
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return ReflectionMethod[]
     */
    protected function getMethods(ReflectionClass $reflectionClass)
    {
        return $reflectionClass->getMethods();
    }
}