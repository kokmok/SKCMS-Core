<?php

namespace SKCMS\CoreBundle\Service;

/**
 * Description of ClassModifier
 *
 * @author jona
 */
class ClassModifier 
{
    
    private $fileContent;
    private $filePath;
    
    public function __construct() {
        ;
    }
    
    /**
     * 
     * @param type $file
     *  the root path of the php class
     * @param 
     * the classname with namespace of the parent
     */
    static function addExtends($file, $extension)
    {
        $data = file_get_contents($file);
        if ($data)
        {
            $lines = explode("\n",$data);
            
            $i = 0;

            foreach ($lines as $line)
            {
                if (preg_match('#^class (.)+#',$line))
                {
//                    if ($force)
//                    {
                        $line = preg_replace('#(class [a-zA-z0-9]+)[ ]+(extends ([a-zA-Z0-9\\\])+)?#', '$1', $line);
//                       
//                    }
                    
                    if (!preg_match('#(class [a-zA-z0-9]+)[ ]+extends ([a-zA-Z0-9\\\])+#', $line))
                    {
                        $line = preg_replace('#(class [A-Z]{1}[a-zA-z0-9]+)#', '$1 extends '.$extension, $line);
                    }
                    
                    $lines[$i]=$line;
                    
                }
                
                $i++;
            }
            
            $newData = implode("\n",$lines);
//           
            file_put_contents($file.'.bkp', $data);
            file_put_contents($file, $newData);
        }

    }
    /**
     * 
     * @param type $file
     * The root path of the file
     */
    static function privateToProtected($file)
    {
        $data = file_get_contents($file);
        if ($data)
        {
            $lines = explode("\n",$data);
            
            $i = 0;

            foreach ($lines as $line)
            {
                if (preg_match('#(.*)private \$id;(.*)#',$line))
                {
                    $lines[$i] = preg_replace('#(.*)private \$id;(.*)#', '$1protected $id;$2', $line);
                }
                $i++;
            }
            $newData = implode("\n",$lines);
            file_put_contents($file.'.bkp', $data);
            file_put_contents($file, $newData);
        }
    }
    
    static function parentBuildForm($filePath)
    {
        
        $data = file_get_contents($filePath);
        if ($data)
        {
            $lines = explode("\n",$data);
            
            $i = 0;
            foreach ($lines as $line)
            {
                if (preg_match('#(.*)function buildForm\(FormBuilderInterface \$builder, array \$options\)(.*)#',$line))
                {
//                    die($line);
                    
                    $lines[$i+1].="\n\t".'parent::buildForm($builder, $options);';
                    
                }
                $i++;
            }
            
            $newData = implode("\n",$lines);


            file_put_contents($filePath, $newData);
           
        }
        
    }
    
    public function removeLineContaining($filePath,$contains)
    {
        $this->filePath = $filePath;
        $this->data = file_get_contents($filePath);
        if ($this->data)
        {
            if (is_array($contains))
            {
                foreach($contains as $contain)
                {
                    $this->findAndRemoveLineContaining($contain);
                }
            }
            else
            {
                $this->findAndRemoveLineContaining($contains);
            }
        }
        
        file_put_contents($filePath, $this->data);
        
    }
    
    
    private function findAndRemoveLineContaining($contains)
    {
           $lines = explode("\n",$this->data);
            
            $i = 0;
            foreach ($lines as $line)
            {
                if (preg_match('#'.$contains.'#',$line))
                {
                    $lines[$i] ="";
                }
                $i++;
            }
            
            $this->data = implode("\n",$lines);


            
           
        
    }
}
