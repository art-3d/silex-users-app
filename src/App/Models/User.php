<?php
namespace App\Models;

use DateTime;
use ReflectionClass;
use ReflectionProperty;

class User
{
    protected $firstname;
    protected $lastname;
    protected $nickname;
    protected $age;
    protected $password;
    private $createdAt;
    private $salt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->salt = uniqid(mt_rand(), true);
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function validate()
    {
        $errors = array();

        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $property) {
            $getter = 'get' . ucfirst($property->getName());
            $value = $this->$getter();

            if (empty(trim($value))) {
                $errors[] = 'All fields are required';
                break;
            }
        }
        if ((int)$this->getAge() <= 0) {
            $errors[] = 'Age should be positive integer number';
        }

        return $errors;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
