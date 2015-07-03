<?php

namespace SKCMS\CoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineEntityCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
/**
 * Description of GenerateEntity
 *
 * @author jona
 */
class GenerateEntityCommand extends GenerateDoctrineEntityCommand
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('skcms:generate:entity')
            ->setAliases(array('skcms:doctrine:entity'))
            ->setDescription('Generates a new Doctrine entity inside a bundle and prepare it for SKCMS')
          
                ;
    }
    
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>This is the modified version for SKCMS</info>');
        $output->writeln('<fg=red>WARNING : This will only works with annotation format</>');
        parent::interact($input, $output);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $input->setOption('format', 'annotation');
//        $input->setOption('with-repository', true);
        parent::execute($input, $output);
        
        $classModifier = $this->getContainer()->get('skcms_core.classmodifier');

        //Direct grabbed from parent function
        $entity = Validators::validateEntityName($input->getOption('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);
        $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
        
        $entityPath = $bundle->getPath().'/Entity/'.str_replace('\\', '/', $entity).'.php';
        
        $classModifier->addExtends($entityPath, '\\SKCMS\\CoreBundle\\Entity\\SKBaseEntity');
        $classModifier->privateToProtected($entityPath);
        $output->writeln(sprintf('SKize entity : '. $entity));

        if ($input->getOption('with-repository') == true)
        {
            $repositoryPath = $bundle->getPath().'/Entity/'.str_replace('\\', '/', $entity).'Repository.php';
            $output->writeln(sprintf('SKize repository'. $repositoryPath));
            $this->addExtends($repositoryPath, '\\SKCMS\\CoreBundle\\Repository\\SKEntityRepository',true);
        }
        
        
        
    }
}
