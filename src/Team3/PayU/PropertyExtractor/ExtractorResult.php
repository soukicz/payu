<?php
/**
 * @author Krzysztof Gzocha <krzysztof.gzocha@xsolve.pl>
 */
namespace Team3\PayU\PropertyExtractor;

/**
 * This class is holding value and property name extracted from given object.
 * It can be returned by {@link ExtractorInterface}
 *
 * Class ExtractorResult
 * @package Team3\PayU\Annotation\Extractor
 */
class ExtractorResult
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $propertyName
     * @param mixed  $value
     */
    public function __construct(
        $propertyName,
        $value
    ) {
        $this->propertyName = $propertyName;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
