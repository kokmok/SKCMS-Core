<?php
namespace SKCMS\CoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineFormCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\GeneratorBundle\Command\Validators;

/**
 * Description of GenerateFormCommand
 *
 * @author jona
 */
class GenerateFormCommand extends GenerateDoctrineFormCommand
{
    
    protected function configure()
    {
        parent::configure();
        $this
            
            ->setDescription('Generates a form type class based on a Doctrine entity For SKCMS')
            
            ->setName('skcms:generate:form')
            ->setAliases(array('generate:skcms:form'))
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        
//        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle).'\\'.$entity;
//        $metadata = $this->getEntityMetadata($entityClass);
        $bundle   = $this->getApplication()->getKernel()->getBundle($bundle);
        
        
        $entityPath = $bundle->getPath().'/Entity/'.$entity.'.php';
        $entityContent = file_get_contents($entityPath);
        
        $extension = '\\SKCMS\\CoreBundle\\Form\\EntityType';
        
        if (preg_match('#SKBasePage#', $entityContent))
        {
            $extension='\\SKCMS\\CoreBundle\\Form\\PageType';
        }
        
        
        $dirPath         = $bundle->getPath().'/Form';
        $classPath = $dirPath.'/'.str_replace('\\', '/', $entity).'Type.php';
        
        $classModifier = $this->getContainer()->get('skcms_core.classmodifier');
        
        $classModifier->addExtends($classPath, $extension);
        $classModifier->parentBuildForm($classPath);
        $classModifier->removeLineContaining($classPath,['slug','userCreate','userUpdate','creationDate','updateDate','draft','position']);
        
        $output->writeln($classPath.' skized');
        
        

    }
}
