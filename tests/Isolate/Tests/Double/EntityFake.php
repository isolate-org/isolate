<?php

namespace Isolate\Tests\Double;

class EntityFake
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var array
     */
    private $items;

    /**
     * @param null $id
     * @param null $firstName
     * @param null $lastName
     */
    public function __construct($id = null, $firstName = null, $lastName = null)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->items = [];
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $newLastName
     */
    public function changeLastName($newLastName)
    {
        $this->lastName = $newLastName;
    }

    /**
     * @return null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $newName
     */
    public function changeFirstName($newName)
    {
        $this->firstName = $newName;
    }

    /**
     * @return null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param $item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items = [])
    {
        $this->items = $items;
    }

    public static function getClassName()
    {
        return __CLASS__;
    }
}
