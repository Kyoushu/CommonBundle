<?php

namespace Kyoushu\CommonBundle\EntityFinder\Transformer;

class RouteParameterValueTransformer
{

    const NULL_PLACEHOLDER = '-';

    const DATETIME_PATTERN = 'c';
    const DATETIME_REGEX = '/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(\+|\-)[0-9]{2}:[0-9]{2}$/';

    /**
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {

        if($value === null){
            return self::NULL_PLACEHOLDER;
        }
        elseif(is_scalar($value)){
            return $value;
        }
        elseif($value instanceof \DateTime){
            return $value->format(self::DATETIME_PATTERN);
        }

        throw new \InvalidArgumentException(
            sprintf(
                '%s does not support %s',
                get_class($this),
                (is_object($value) ? get_class($value) : gettype($value))
            )
        );

    }

    /**
     * @param string $value
     * @return mixed
     */
    public function inverseTransform($value)
    {

        if(!is_scalar($value)){
            throw new \InvalidArgumentException(
                sprintf(
                    'value must be scalar, %s given',
                    gettype($value)
                )
            );
        }

        if($value === self::NULL_PLACEHOLDER){
            return null;
        }
        elseif(preg_match(self::DATETIME_REGEX, $value)){
            return new \DateTime($value);
        }
        else{
            return $value;
        }

    }

}