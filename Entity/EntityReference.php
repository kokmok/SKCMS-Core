<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntityReference
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EntityReference
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="className", type="string", length=255)
     */
    private $className;

    /**
     * @var string
     *
     * @ORM\Column(name="foreignKey", type="string", length=255)
     */
    private $foreignKey;
    
    private $entity;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set className
     *
     * @param string $className
     * @return EntityReference
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string 
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set foreignKey
     *
     * @param string $foreignKey
     * @return EntityReference
     */
    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    /**
     * Get foreignKey
     *
     * @return string 
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    public function getEntity()
    {
        return $this->entity;
    }
}
