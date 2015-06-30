<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * MenuElement
 *
 * @Gedmo\Tree(type="nested")
 * @Gedmo\TranslationEntity(class="SKCMS\CoreBundle\Entity\Translation\EntityTranslation")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Entity\MenuElementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MenuElement implements Translatable
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
     *
     * @var string
     * @ORM\Column(name="textId",type="string",length=255)
     */
    protected $textId;


    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;
    
    
    /**
     * Post locale
     * Used locale to override Translation listener's locale
     *
     * @Gedmo\Locale
     */
    protected $locale;
    
    /*
     *
     * @ORM\ManyToOne(targetEntity="SKCMS\CoreBundle\Entity\Menu",inversedBy="elements",cascade="all")
     */
//    protected $menu;
    
    protected $link;
    protected $targetEntity;
    
    
    /**
     * @ORM\Column(name="entityId", type="integer",nullable=true)
     * 
     */
    protected $entityId;
    
    /**
     * @ORM\Column(name="entityClass", type="string",length=255,nullable=true)
     * 
     */
    protected $entityClass;
    
    
    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuElement", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuElement", mappedBy="parent")
     * 
     */
    protected $children;

    
    
    protected $entityConfigName = 'Page';
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
     * @return MenuElement
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
     * Set position
     *
     * @param integer $position
     * @return MenuElement
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

   

    /**
     * Set menu
     *
     * @param \SKCMS\CoreBundle\Entity\Menu $menu
     * @return MenuElement
     */
    public function setMenu(\SKCMS\CoreBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \SKCMS\CoreBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->position =0;
        $this->textId = uniqid();
        
    }

    

    /**
     * Set parent
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $parent
     * @return MenuElement
     */
    public function setParent(\SKCMS\CoreBundle\Entity\MenuElement $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \SKCMS\CoreBundle\Entity\MenuElement 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $children
     * @return MenuElement
     */
    public function addChild(\SKCMS\CoreBundle\Entity\MenuElement $children)
    {
        $this->children[] = $children;
        $children->setParent($this);
        return $this;
    }

    /**
     * Remove children
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $children
     */
    public function removeChild(\SKCMS\CoreBundle\Entity\MenuElement $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $children
     * @return \SKCMS\CoreBundle\Entity\MenuElement
     * 
     */
    public function setChildren(\Doctrine\Common\Collections\ArrayCollection $children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     * @return MenuElement
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }
    
    public function getEntityConfigName()
    {
        return $this->entityConfigName;
    }
    
    public function __toString()
    {
        return $this->name;
    }
    
    /**
     * Sets translatable locale
     *
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set textId
     *
     * @param string $textId
     * @return MenuElement
     */
    public function setTextId($textId)
    {
        $this->textId = $textId;
    
        return $this;
    }

    /**
     * Get textId
     *
     * @return string 
     */
    public function getTextId()
    {
        return $this->textId;
    }

    /**
     * Add children
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $children
     * @return MenuElement
     */
    public function addChildren(\SKCMS\CoreBundle\Entity\MenuElement $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $children
     */
    public function removeChildren(\SKCMS\CoreBundle\Entity\MenuElement $children)
    {
        $this->children->removeElement($children);
    }
    
    public function getLink()
    {
        return $this->link;
    }
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }
    
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }
    public function setTargetEntity(SKBaseEntity $entity)
    {
        $this->targetEntity = $entity;
        return $this;
    }
    
    public function setEntityClass($entityClass)
    {
        $this->entityClass = substr($entityClass,0,1) == '\\' ? $entityClass : '\\'.$entityClass;
        return $this;
    }
    
    public function getEntityClass()
    {
        return $this->entityClass;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function generateTextId()
    {
        if (null == $this->textId || !strlen($this->textId))
        {
            $this->textId = uniqid();
//            die($this->textId);
        }
    }
    
}