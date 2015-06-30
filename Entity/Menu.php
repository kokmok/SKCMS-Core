<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Entity\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /*
     *
     * @ORM\OneToMany(targetEntity="MenuElement",mappedBy="menu",cascade="all")
     */
//    private $elements;


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
     * @return Menu
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
     * Constructor
     */
    public function __construct()
    {
        $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add elements
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $elements
     * @return Menu
     */
    public function addElement(\SKCMS\CoreBundle\Entity\MenuElement $elements)
    {
        $this->elements[] = $elements;
        $elements->setMenu($this);
        die('elements added');
        return $this;
    }

    /**
     * Remove elements
     *
     * @param \SKCMS\CoreBundle\Entity\MenuElement $elements
     */
    public function removeElement(\SKCMS\CoreBundle\Entity\MenuElement $elements)
    {
        $this->elements->removeElement($elements);
        $elements->setMenu(null);
    }
    
    public function setElements(array $elements)
    {
        die('elements sets');
    }

    /**
     * Get elements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getElements()
    {
        return $this->elements;
    }
}
