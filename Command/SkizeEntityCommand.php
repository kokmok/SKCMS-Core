<?php
namespace SKCMS\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * Description of SkizeEntity
 *
 * @author Jona
 */
class SkizeEntityCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this
            ->setName('skcms:skize:entity')
            ->setDescription('Set extends for entity, its repository and type')
            ->addArgument('name', InputArgument::REQUIRED, 'Wich entity would you skize')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $manager = new \Doctrine\Bundle\DoctrineBundle\Mapping\DisconnectedMetadataFactory($doctrine);
        
        $name = strtr($input->getArgument('name'), '/', '\\');

        if (false !== $pos = strpos($name, ':')) {
            $name = $this->getContainer()->get('doctrine')->getEntityNamespace(substr($name, 0, $pos)).'\\'.substr($name, $pos + 1);
        }
        

        $output->writeln(sprintf('SKize entity'. $name));
        
        $entityPath = __DIR__.'/../../../'.strtr($name, '\\', '/').'.php';
        $this->addExtends($entityPath, '\\SKCMS\\CoreBundle\\Entity\\SKBaseEntity');
        
        $repositoryPath =  __DIR__.'/../../../'.strtr($name, '\\', '/').'Repository.php';
        $output->writeln(sprintf('SKize repository'. $repositoryPath));
        $this->addExtends($repositoryPath, '\\SKCMS\\CoreBundle\\Repository\\SKEntityRepository',true);
        
        $explodedPath = explode('\\',$name);
        
        $explodedPath[count($explodedPath)-2] = 'Form';
        $explodedPath[count($explodedPath)-1] .= 'Type';
        
        $formPath = implode('/',$explodedPath);
        $formPath =  __DIR__.'/../../../'.$formPath.'.php';
        $this->addExtends($formPath, '\\SKCMS\\CoreBundle\\Form\\EntityType',true);
        $this->parentBuildForm($formPath);
        
        $output->writeln(sprintf('SKize entity'. $entityPath));
                
        
    }
    
    
    private function addExtends($filePath,$extends,$force = false)
    {
        
        
        
//        $file = fopen($filePath, "a+");
        $data = file_get_contents($filePath);
        if ($data)
        {

            $lines = explode("\n",$data);
            
            $i = 0;
//            die(print_r($lines,true));
            foreach ($lines as $line)
            {
                if (preg_match('#^class (.)+#',$line))
                {
                    if ($force)
                    {
                        $line = preg_replace('#(class [a-zA-z0-9]+)[ ]+(extends ([a-zA-Z0-9\\\])+)?#', '$1', $line);
//                       
                    }
                    
                    if (!preg_match('#(class [a-zA-z0-9]+)[ ]+extends ([a-zA-Z0-9\\\])+#', $line))
                    {
                        $line = preg_replace('#(class [A-Z]{1}[a-zA-z0-9]+)#', '$1 extends '.$extends, $line);
                        
                        
                    }
                    
                    $lines[$i]=$line;
                    
                }
                if (preg_match('#(.*)private \$id;(.*)#',$line))
                {
                    $lines[$i] = preg_replace('#(.*)private \$id;(.*)#', '$1protected $id;$2', $line);
                }
                $i++;
            }
            
            $newData = implode("\n",$lines);
//           
            file_put_contents($filePath, $newData);

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



//$manager = new DisconnectedMetadataFactory($this->getContainer()->get('doctrine'));