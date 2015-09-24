<?php
namespace Application\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
  * Handle country list and properties
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  * @ORM\Entity
  */
class Country {

    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $iso;

    /** @ORM\Column(type="string") */
    protected $name;

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

    public function getIso()
    {
        return $this->iso;
    }

    public function setIso($value)
    {
        $this->iso = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
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
