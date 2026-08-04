<?php

namespace SigmaPHP\Console\Tests;

/**
 * Helpers Trait.
 */
trait Helpers
{
    /**
     * Get value of property.
     *
     * @param string $class
     * @param object $object
     * @param string $property
     * @return mixed
     */
    private function inspectProperty($class, $object, $property)
    {
        $inspect = new \ReflectionProperty($class, $property);
        return $inspect->getValue($object);
    }
}
