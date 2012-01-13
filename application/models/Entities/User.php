<?php

namespace Entities;

/**
 * @Entity
 * @Table(name="users")
 */
class User extends AbstractEntity
{

    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="first_name", type="text")
     */
    protected $firstName;

    /**
     * @var string
     * @Column(name="last_name", type="text")
     */
    protected $lastName;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $email;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $password;

    /**
     * @var int
     */
    protected $role;

    /**
     * @var \DateTime
     * @Column(type="datetime", name="date_added")
     */
    protected $dateAdded;

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    protected $status;
    /**
     * @var \DateTime
     * @Column(type="datetime",nullable=true)
     */
    protected $onlineLast;

    /**
     * @var boolean
     * @Column(type="boolean",nullable=true)
     */
    protected $online = false;

    public function __construct($firstName, $email, $password)
    {
        $this->firstName = $firstName;
        $this->password = $password;
        $this->email = $email;
        $this->dateAdded = new \DateTime('now');
    }

    /**
     * @param \DateTime $dateAdded
     * @return \Entities\User
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param string $email
     * @return \Entities\User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $firstName
     * @return \Entities\User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $lastName
     * @return \Entities\User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return join(' ', array($this->getFirstName(), $this->getLastName()));
    }

    /**
     * @param string $password
     * @return \Entities\User
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param int $role
     * @return \Entities\User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param boolean $status
     * @return \Entities\User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $online
     * @return \Entities\User
     */
    public function setOnline($online)
    {
        $this->online = $online;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param \DateTime $onlineLast
     * @return \Entities\User
     */
    public function setOnlineLast($onlineLast)
    {
        $this->onlineLast = $onlineLast;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOnlineLast()
    {
        return $this->onlineLast;
    }
}
