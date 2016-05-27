<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;


/** 
 *
 *  @ORM\MappedSuperclass 
 *  @Gedmo\TranslationEntity(class="SKCMS\CoreBundle\Entity\Translation\EntityTranslation")  
 */
class SKBaseEntity implements Translatable
{
    /** 
     * @ORM\Column(name="id",type="integer") 
     */
    protected $id;

    /**
     * @ORM\Column(name="creationDate", type="datetime")
     */
    protected $creationDate;

    /**
     *  @ORM\Column(name="updateDate", type="datetime")
     */
    protected $updateDate;

    /**
     * @ORM\Column(name="draft", type="boolean")
     */
    protected $draft;
    
    /**
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;
    
    /**
     *
     *  @ORM\ManyToOne(targetEntity="SKCMS\UserBundle\Entity\User")
     *  @Gedmo\Blameable(on="create")
     */
    protected $userCreate;
    /**
     *
     *  @ORM\ManyToOne(targetEntity="SKCMS\UserBundle\Entity\User")
     * @Gedmo\Blameable(on="update")
     */
    protected $userUpdate;
    
    /**
     * Post locale
     * Used locale to override Translation listener's locale
     *
     * @Gedmo\Locale
     */
    protected $locale;
    
    
    protected $slug;

    /**
     *
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Translatable
     */
    protected $SeoTitle;


    /**
     *
     * @ORM\Column(type="text",nullable=true)
     * @Gedmo\Translatable
     */
    protected $SeoDescription;

    
    public function __construct()
    {
        $this->draft = false;
        $this->creationDate = new \DateTime();
        $this->updateDate = new \DateTime();
        $this->position = 0;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return SKBaseEntity
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return SKBaseEntity
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set draft
     *
     * @param boolean $draft
     * @return SKBaseEntity
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft
     *
     * @return boolean 
     */
    public function getDraft()
    {
        return $this->draft;
    }
    
    public function getUserCreate()
    {
        return $this->userCreate;
    }
    
    public function setUserCreate( \SKCMS\UserBundle\Entity\User $user )
    {
        $this->userCreate = $user;
    }
    
    public function getUserUpdate()
    {
        return $this->userUpdate;
    }
    
    public function setUserUpdate( \SKCMS\UserBundle\Entity\User $user )
    {
        $this->userUpdate = $user;
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
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    
    public function __toString()
    {
        if (property_exists($this, 'name') && $this->getName() !==null)
        {
            return $this->getName() !==null ? $this->getName() : '';
        }
        else if (property_exists($this, 'title') && $this->getTitle() !==null)
        {
            return $this->getTitle()!==null ? $this->getTitle() : '';
        }

        return '';
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getSeoTitle()
    {
        return $this->SeoTitle;
    }

    /**
     * @param mixed $SeoTitle
     */
    public function setSeoTitle($SeoTitle)
    {
        $this->SeoTitle = $SeoTitle;
    }

    /**
     * @return mixed
     */
    public function getSeoDescription()
    {
        return $this->SeoDescription;
    }

    /**
     * @param mixed $SeoDescription
     */
    public function setSeoDescription($SeoDescription)
    {
        $this->SeoDescription = $SeoDescription;
    }
}
