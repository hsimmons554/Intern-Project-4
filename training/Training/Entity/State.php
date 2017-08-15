<?php

namespace Training\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="states")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="StateRepository")
 * @author Harold Simmons <harold@potenzainnovations.com>
 */
class State
{

    /**
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *
     * @var string $state_name
     * @ORM\Column(type="string",length=60,nullable=false)
     */
    private $state_name;

    /**
     *
     * @var string $state_abbreviation
     * @ORM\Column(type="string",length=60,nullable=false)
     */
     private $state_abbreviation;

     /**
     * @var datetime $created_at
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

     /**
     * @var datetime $updated_at
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     *
     * @var boolean
     * @ORM\Column(type="boolean",length=1,nullable=true)
     */
    private $active;

     /**
     *
     * \Doctrine\Entity\Manager
     */
    public $em = null;

    /**
     * Bidirectional - Many States have Many People
     * @ORM\ManyToMany(targetEntity="Training\Entity\Person", mappedBy="states")
     */
     private $people;

    /**
     * Get Doctrine Entity Manager
     * @return \Doctrine\Entity\Manager
     */
    public function getEntityManager() {
        if($this->em===null){
            $dc = \Zend_Registry::get('doctrine');
            $this->em = $dc->getEntityManager();
        }
        return $this->em;
    }

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }

    /**
     * Establish M:M relation with people
     */
     public function getPeople()
     {
       return $this->people;
     }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray() {
        $vars = get_object_vars($this);
        return $vars;
    }

    /**
     * Create an entity with the given data
     *
     * @param array $data
     * @return object
     */
    public function createEntity(array $data)
    {
        $metadata = $this->getEntityManager()->getClassMetadata(get_class($this));
        if(isset($data['id'])){
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }
        $entity = $metadata->newInstance();
        return $this->updateEntity($entity,$data);
    }

    /**
     * Update an entity with the given data
     *
     * @param array $data
     * @return object
     */
    public function updateEntity($entity, array $data)
    {
        $metadata = $this->getEntityManager()->getClassMetadata(get_class($this));
        foreach($data as $property => $value){
            if(!$metadata->reflClass->hasProperty($property))
                continue;
            $metadata->setFieldValue($entity, $property, $value);
        }
        return $entity;
    }

    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->created_at = new \DateTime;
        $this->updated_at = new \DateTime;
    }

    /** @ORM\PreUpdate */
    public function preUpdate()
    {
        $this->updated_at = new \DateTime;
    }

}
