<?php

namespace SKCMS\CoreBundle\Service;

/**
 * Description of ClassModifier
 *
 * @author jona
 */
class ClassModifier 
{
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
    
    private function parentBuildForm($filePath)
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

//            die($newData);
//            $newdata = preg_replace('#(function buildForm\(FormBuilderInterface $builder, array $options)
//    \{)#', '$1 \n  parent::buildForm($builder, $options); \n', $data);
//            



            file_put_contents($filePath, $newData);
           
        }
        
    }
}
