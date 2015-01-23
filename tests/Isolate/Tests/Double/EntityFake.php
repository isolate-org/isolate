<?php

namespace Isolate\Tests\Double;

class EntityFake
{
    /**
     * @var null
     */
    private $id;
    /**
     * @var null
     */
    private $firstName;
    /**
     * @var null
     */
    private $lastName;

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

    public static function getClassName()
    {
        return __CLASS__;
    }
}
