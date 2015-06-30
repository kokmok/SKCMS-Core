<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SKCMS\CoreBundle\Slug\SKSlug as SKSlug;


/** 
 * @ORM\MappedSuperclass 
 *
 * 
 */
class SKBasePage extends SKBaseEntity 
{
//     @SKSlug(field="title")
    /**
     * @Gedmo\Slug(fields={"title"},updatable=false)
     * @Gedmo\Translatable
     * @ORM\Column(length=128)
     * 
     */
    protected $slug; 

    /**
     * 
     * @ORM\Column(type="string")
     * @Gedmo\Translatable
     */
    protected $title;
    
    /**
     * @ORM\ManyToOne(targetEntity = "SKCMS\CoreBundle\Entity\PageTemplate")
     */
    protected $template;
    
    /**
     * @ORM\ManyToMany(targetEntity = "SKCMS\CoreBundle\Entity\EntityList")
     */
    protected $lists;

    
    public function __construct()
    {
        parent::__construct();
        $this->list = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function setTemplate(PageTemplate $template)
    {
        $this->template = $template;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function addList(EntityList $list)
    {
        $this->lists->add($template);
    }
    public function removeList(EntityList $list)
    {
        $this->lists->remove($template);
    }
    public function setLists(\Doctrine\Common\Collections\ArrayCollection $list)
    {
        $this->lists = $list;
    }
    
    public function getLists()
    {
        return $this->lists;
    }
    
    
    
}
