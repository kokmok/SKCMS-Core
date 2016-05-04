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
    
    /**
     * @ORM\ManyToMany(targetEntity = "SKCMS\CoreBundle\Entity\MenuElement")
     */
    protected $menus;
    
    /**
     *
     * @ORM\Column(name="minRoleAccess",type="string",length=255,nullable=true)
     */
    protected $minRoleAccess;
    
    /**
     *
     * @ORM\Column(name="redirectRoute",type="string",length=255,nullable=true)
     */
    protected $redirectRoute;
    
    /**
     *
     * @ORM\Column(name="forward",type="boolean",nullable=true)
     */
    protected $forward;

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
        parent::__construct();
        $this->lists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->menus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->minRoleAccess = 'ANON';
        $this->forward = false;
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
        return $this;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function setTemplate(PageTemplate $template)
    {
        $this->template = $template;
        return $this;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function addList(EntityList $list)
    {
        $this->lists->add($list);
        return $this;
    }
    public function removeList(EntityList $list)
    {
        $this->lists->remove($list);
        return $this;
    }
    public function setLists(\Doctrine\Common\Collections\ArrayCollection $list)
    {
        $this->lists = $list;
        return $this;
    }
    
    public function getLists()
    {
        return $this->lists;
    }
    
    public function addMenu(MenuElement $menu)
    {
        $this->menus->add($menu);
        return $this;
    }
    public function removeMenu(MenuElement $menu)
    {
        $this->menus->remove($menu);
        return $this;
    }
    public function getMenus()
    {
        return $this->menus;
        
    }
    
    public function getMinRoleAccess()
    {
        return $this->minRoleAccess;
    }
    
    public function setMinRoleAccess($role)
    {
        $this->minRoleAccess = $role;
        return $this;
    }
    public function getRedirectRoute()
    {
        return $this->redirectRoute;
    }
    
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = $redirectRoute;
        return $this;
    }
    
    public function getForward()
    {
        return $this->forward;
    }
    
    public function setForward($forward)
    {
        $this->forward = $forward;
        return $this;
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
