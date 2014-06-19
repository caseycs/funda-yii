<?php
class ReflectionHelper
{
    public static function setProperty($Object, $property, $value)
    {
        $R = new \ReflectionClass($Object);
        $P = $R->getProperty($property);
        $P->setAccessible(true);
        $P->setValue($Object, $value);
    }

    public static function getProperty($Object, $property)
    {
        $R = new \ReflectionClass($Object);
        $P = $R->getProperty($property);
        $P->setAccessible(true);

        return $P->getValue($Object);
    }

    public static function call($Object, $method, $args = array())
    {
        $R = new \ReflectionClass($Object);
        $M = $R->getMethod($method);
        $M->setAccessible(true);

        return $M->invokeArgs($Object, $args);
    }
}
