<?php
namespace Application\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
  * User
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  * @ORM\Entity
  */
class User {

    /**
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="AUTO")
      * @ORM\Column(type="integer")
      */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $company;

    /** @ORM\Column(type="string") */
    protected $first_name;

    /** @ORM\Column(type="string") */
    protected $last_name;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $password;

    /** @ORM\Column(type="integer") */
    protected $status;

    /** @ORM\Column(type="string") */
    protected $token;

    /** @ORM\Column(type="string") */
    protected $phone;

    /** @ORM\Column(type="string") */
    protected $address;

    /** @ORM\Column(type="string") */
    protected $zipcode;

    /** @ORM\Column(type="string") */
    protected $city;

    /** @ORM\Column(type="string") */
    protected $country;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $last_modified
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $last_modified;


    public function getId()
    {
        return $this->id;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($value)
    {
        $this->company = $value;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($value)
    {
        $this->first_name = $value;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($value)
    {
        $this->last_name = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function getStatus()
    {
        return $this->Status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($value)
    {
        $this->phone = $value;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($value)
    {
        $this->address = $value;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function setZipcode($value)
    {
        $this->zipcode = $value;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($value)
    {
        $this->city = $value;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($value)
    {
        $this->country = $value;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($value)
    {
        $this->created = $value;
    }

    public function getLastModified()
    {
        return $this->last_modified;
    }

    public function setLastModified($value)
    {
        $this->last_modified = $value;
    }

}
