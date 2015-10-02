<?php
namespace Application\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
  * Contact form
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  * @ORM\Entity
  */
class Contact
{

    /**
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="AUTO")
      * @ORM\Column(type="integer")
      */
    protected $id;

    /** @ORM\Column(type="text") */
    protected $message;

    /** @ORM\Column(type="string") */
    protected $company;

    /** @ORM\Column(type="string") */
    protected $first_name;

    /** @ORM\Column(type="string") */
    protected $last_name;

    /** @ORM\Column(type="integer") */
    protected $user_id;

    /** @ORM\Column(type="integer") */
    protected $status;

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

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($value)
    {
        $this->user_id = $value;
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

    public function getMessage()
    {
        return $this->Message;
    }

    public function setMessage($value)
    {
        $this->message = $value;
    }

    public function getStatus()
    {
        return $this->Status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
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
