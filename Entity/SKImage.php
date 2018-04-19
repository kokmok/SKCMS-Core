<?php

namespace SKCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SKImage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Entity\SKImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SKImage implements \ArrayAccess
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
     * @var integer
     *
     * @ORM\Column(name="optimalWidth", type="integer")
     */
    protected $optimalWidth=0;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimalHeight", type="integer")
     */
    protected $optimalHeight=0;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    protected $picture;
    
    /**
     * @var string
     *
     * @ORM\Column(name="optimalPicture", type="string", length=255)
     */
    protected $optimalPicture;


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
     * Set optimalWidth
     *
     * @param integer $optimalWidth
     * @return SKImage
     */
    public function setOptimalWidth($optimalWidth)
    {
        $this->optimalWidth = $optimalWidth;
    
        return $this;
    }

    /**
     * Get optimalWidth
     *
     * @return integer 
     */
    public function getOptimalWidth()
    {
        return $this->optimalWidth;
    }

    /**
     * Set optimalHeight
     *
     * @param integer $optimalHeight
     * @return SKImage
     */
    public function setOptimalHeight($optimalHeight)
    {
        $this->optimalHeight = $optimalHeight;
    
        return $this;
    }

    /**
     * Get optimalHeight
     *
     * @return integer 
     */
    public function getOptimalHeight()
    {
        return $this->optimalHeight;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return SKImage
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }
    /**
     * Set optimalpicture
     *
     * @param string $optimalPicture
     * @return SKImage
     */
    public function setOptimalPicture($optimalPicture)
    {
        $this->optimalPicture = $optimalPicture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getOptimalPicture()
    {
        return $this->optimalPicture;
    }
    
    public function getThumb($width,$height=0)
    {
        
        if (!file_exists($this->getThumbAbsolutePath($width,$height)))
        {
//            dump($this->picture);
//            die($this->getThumbAbsolutePath($width,$height));  
            $this->generateTransformedImage($width,$height);
        }
        
        return $this->getThumbPath($width,$height);
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function transformImage()
    {
        if ($this->optimalHeight>0 || $this->optimalWidth >0)
        {
            if (!$this->optimalExistsAndOk($this->optimalWidth,$this->optimalHeight))
            {
                $this->optimalPicture = $this->generateTransformedImage($this->optimalWidth,$this->optimalHeight);
            }
        }
        else
        {
            $this->optimalPicture = $this->picture;
        }
        
    }
    
    protected function generateTransformedImage($width,$height)
    {
        
        $pathInfo = pathinfo($this->picture);
        $mime = mime_content_type($this->getAbsolutePicturePath());
        $tranformedDir = $pathInfo['dirname'].'/_'.$width.'_'.$height;
//        die('generateTransformedImage ');
        $originalImage = $this->createImageFrom($mime);
        
        $originalWidth = imagesx( $originalImage );
        $originalHeight = imagesy( $originalImage );
        $originalRatio = $originalWidth/$originalHeight;
        
        
        if ($height == 0)
        {
            $newWidth = $width;
            $newHeight = floor( $originalHeight * ( $width / $originalWidth ) );       
        }
        else if ($width == 0)
        {
            $newHeight = $height;
            $newWidth = floor( $originalWidth * ( $height / $originalHeight ) );     
        }
        else
        {
            $newWidth = $width;
            $newHeight = $height;
        }
        
        $newRatio = $newWidth/$newHeight;
        
        $x=0;
        $y=0;
        if ($originalRatio<$newRatio)
        {
            $cropHeight = ($newHeight/$newWidth)*$originalWidth;
            $y = round(($originalHeight-$cropHeight)/2);				
        }
        elseif ($originalRatio>$newRatio)
        {
            $cropWidth = ($newWidth/$newHeight)*$originalHeight;
            $x = round(($originalWidth-$cropWidth)/2);
        }
        
        $transformedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        
        if ($mime == 'image/png')
        {
            imagealphablending($transformedImage, false);
            imagesavealpha( $transformedImage, true );
        }
        $copiedHeight = $originalHeight-(2*$y);
        $copiedWidth = $originalWidth-(2*$x);
        
        @imagecopyresampled($transformedImage, $originalImage, 0, 0, $x, $y, $newWidth, $newHeight, $copiedWidth, $copiedHeight);
        
        if (!is_dir($this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height)))
        {
            mkdir($this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height));
        }
        chmod($this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height), 0777);
        
        switch ($mime)
        {
                case 'image/gif' : @imagegif($transformedImage,$this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height).'/'.urldecode($pathInfo['basename']));
                break;
                case 'image/jpeg' : imagejpeg($transformedImage,$this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height).'/'.urldecode($pathInfo['basename']), 90);
                break;
                case 'image/png' :  @imagepng($transformedImage,$this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height).'/'.urldecode($pathInfo['basename']), 9);
                break;
        }
        
        imagedestroy($originalImage);
        imagedestroy($transformedImage);
        
        chmod($this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height), 0755);
        chmod($this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height).'/'.urldecode($pathInfo['basename']), 0644);
        
        return $this->getTransformedDir($width,$height).'/'.$pathInfo['basename'];
        
        
        
        
    }
    
    protected function createImageFrom($mime)
    {
        
        switch ($mime)
        {
            case 'image/jpeg':
                
//                imagecreatefromjpeg($this->getAbsolutePicturePath());
//                die('image '.imagecreatefromjpeg($this->getAbsolutePicturePath()));
                return imagecreatefromjpeg($this->getAbsolutePicturePath());
                
                break;
            case 'image/png' :
                return imagecreatefrompng($this->getAbsolutePicturePath());
                break;
            case 'image/gif' :
                return imagecreatefromgif($this->getAbsolutePicturePath());
                break;
        }
    }
    
    protected function getTransformedDir($width,$height)
    {
        $originalDir = pathinfo($this->picture);
        $tranformedDir = $originalDir['dirname'].'/_'.$width.'_'.$height;
        
        return $tranformedDir;
    }
    
  
    protected function getThumbAbsolutePath($width,$height)
    {
        $pathInfo = pathinfo($this->picture);
        return $this->getImageFullRoot().'/'.$this->getTransformedDir($width,$height).'/'.urldecode($pathInfo['basename']);
    }
    protected function getThumbPath($width,$height)
    {
        
        $pathInfo = pathinfo($this->picture);
        return $this->getTransformedDir($width,$height).'/'.urldecode($pathInfo['basename']);
    }
    
    protected function optimalExistsAndOk()
    {
        if (null === $this->optimalPicture)
        {
            return false;
        }
        
        if (file_exists($this->getImageFullRoot().$this->optimalPicture))
        {
            $size = getimagesize($this->getImageFullRoot().$this->optimalPicture);
            if ($size[0] == $this->optimalWidth && $size[1] == $this->optimalHeight)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        
    }
    
    protected function getImageFullRoot()
    {
        return __DIR__.'/../../../../../../web';
    }
    
    protected function getAbsolutePicturePath()
    {
        return $this->getImageFullRoot().'/'.urldecode($this->getPicture());
    }
    
    
    
    public function __toString()
    {
        if ($this->optimalExistsAndOk())
        {
            return $this->optimalPicture;
        }
        else
        {
            return urldecode($this->picture);
        }
        
    }

    public function offsetExists($offset)
    {
        return $offset>1;
    }

    public function offsetGet($offset)
    {
        return $offset == 0 ? $this->__toString() : null;
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}
