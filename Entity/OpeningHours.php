<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpeningHours
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Entity\OpeningHoursRepository")
 */
class OpeningHours extends \SKCMS\CoreBundle\Entity\SKBaseEntity
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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="openAt", type="time")
     */
    private $openAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closeAt", type="time")
     */
    private $closeAt;


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
     * @return OpeningHours
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
     * Set text
     *
     * @param string $text
     * @return OpeningHours
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set openAt
     *
     * @param \DateTime $openAt
     * @return OpeningHours
     */
    public function setOpenAt($openAt)
    {
        $this->openAt = $openAt;

        return $this;
    }

    /**
     * Get openAt
     *
     * @return \DateTime 
     */
    public function getOpenAt()
    {
        return $this->openAt;
    }

    /**
     * Set closeAt
     *
     * @param \DateTime $closeAt
     * @return OpeningHours
     */
    public function setCloseAt($closeAt)
    {
        $this->closeAt = $closeAt;

        return $this;
    }

    /**
     * Get closeAt
     *
     * @return \DateTime 
     */
    public function getCloseAt()
    {
        return $this->closeAt;
    }
}
