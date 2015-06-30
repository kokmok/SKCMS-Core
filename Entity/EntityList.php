<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntityList
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Entity\EntityListRepository")
 */
class EntityList
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255)
     */
    protected $entity;

    /**
     * @var integer
     *
     * @ORM\Column(name="_limit", type="integer")
     */
    protected $limit;

    /**
     * @var string
     *
     * @ORM\Column(name="_order", type="string", length=255)
     */
    protected $order;

    /**
     * @var string
     *
     * @ORM\Column(name="orderBy", type="string", length=255)
     */
    protected $orderBy;


    
    
    public function __toString()
    {
        return $this->name;
    }
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
     * Set name
     *
     * @param string $name
     * @return EntityList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set entity
     *
     * @param string $entity
     * @return EntityList
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set limit
     *
     * @param integer $limit
     * @return EntityList
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get limit
     *
     * @return integer 
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set order
     *
     * @param string $order
     * @return EntityList
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return string 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set orderBy
     *
     * @param string $orderBy
     * @return EntityList
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get orderBy
     *
     * @return string 
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }
}
