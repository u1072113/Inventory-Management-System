<?php namespace Inventory\Presenters;

/**
 * Class Presenter
 * @package Inventory\Presenters
 */
abstract class Presenter
{

    /**
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }
        return $this->entity->{$property};
    }
}
