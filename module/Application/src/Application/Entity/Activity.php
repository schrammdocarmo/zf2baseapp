<?php
namespace Application\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
  * Activity tracking
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  * @ORM\Entity
  */
class Activity {

    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

    /**
    * @ORM\Column(type="integer")
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    protected $user_id;

    /** @ORM\Column(type="string") */
    protected $description;

    /** @ORM\Column(type="string") */
    protected $uri;

    /** @ORM\Column(type="string") */
    protected $post;

    /** @ORM\Column(type="string") */
    protected $query;

    /** @ORM\Column(type="string") */
    protected $object;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;


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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($value)
    {
        $this->uri = $value;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($value)
    {
        $this->post = $value;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($value)
    {
        $this->query = $value;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($value)
    {
        $this->object = $value;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($value)
    {
        $this->created = $value;
    }


}
