<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
/**
 * ContactInfos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Entity\ContactInfosRepository")
 * @Gedmo\TranslationEntity(class="SKCMS\CoreBundle\Entity\Translation\EntityTranslation")  
 */
class ContactInfos implements Translatable
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
     * @Gedmo\Translatable
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="phone", type="string", length=255)
     */
    protected $phone;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="street", type="string", length=255)
     */
    protected $street;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="streetNumber", type="string", length=255)
     */
    protected $streetNumber;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="city", type="string", length=255)
     */
    protected $city;
    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="postalCode", type="string", length=255)
     */
    protected $postalCode;
    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="country", type="string", length=255)
     */
    protected $country;
    /**
     * @var string
     * @ORM\Column(name="facebookLink", type="string", length=255)
     */
    protected $facebookLink;
    
    /**
     * Post locale
     * Used locale to override Translation listener's locale
     *
     * @Gedmo\Locale
     */
    protected $locale;
    
//    /**
//     * @ORM\ManyToMany(targetEntity="SKCMS\CoreBundle\Entity\OpeningHours")
//     */
//    protected $openingHours;


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
     * Set email
     *
     * @param string $email
     * @return ContactInfos
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return ContactInfos
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return ContactInfos
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     * @return ContactInfos
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
    
        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string 
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return ContactInfos
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return ContactInfos
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return ContactInfos
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
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
    public function getTranslatableLocale()
    {
        return $this->locale ;
    }
     /**
     * Sets translatable locale
     *
     * @param string $locale
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;
        return $this;
    }
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }
    
    public function addOpeningHours(OpeningHours $OpeningHours)
    {
        $this->openingHours->add($OpeningHours);
        return $this;
    }
    public function removeOpeningHours(OpeningHours $OpeningHours)
    {
        $this->openingHours->remove($OpeningHours);
        return $this;
    }
    public function getOpeningHours()
    {
        return $this->openingHours;
    }
}