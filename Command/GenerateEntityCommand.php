<?php

namespace SKCMS\CoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineEntityCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
//use SKCMS\CoreBundle\Validator\Validators;

/**
 * Description of GenerateEntity
 *
 * @author jona
 */
class GenerateEntityCommand extends GenerateDoctrineEntityCommand
{
    const CLASS_PARENT = '\\SKCMS\\CoreBundle\\Entity\\SKBaseEntity';
    const REPO_PARENT = '\\SKCMS\\CoreBundle\\Repository\\SKEntityRepository';
    
    
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('skcms:generate:entity')
            ->setAliases(array('generate:skcms:entity'))
            ->setDescription('Generates a new Doctrine entity inside a bundle and prepare it for SKCMS')
            ->addOption('add-to-menu', null, InputOption::VALUE_NONE, 'Whether to add entity to the menu')
            ->addOption('beauty-name', null, InputOption::VALUE_OPTIONAL, 'The beauty name in the menu')
            ->addOption('menu-group', null, InputOption::VALUE_OPTIONAL, 'The menu where it\'s displayed in the admin')
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
        
        $classModifier->addExtends($entityPath, self::CLASS_PARENT);
        $classModifier->privateToProtected($entityPath);
        $output->writeln(sprintf('SKize entity : '. $entity));

        if ($input->getOption('with-repository') == true)
        {
            $repositoryPath = $bundle->getPath().'/Entity/'.str_replace('\\', '/', $entity).'Repository.php';
            $output->writeln(sprintf('SKize repository'. $repositoryPath));
            $classModifier->addExtends($repositoryPath, self::REPO_PARENT,true);
        }
        
        return;
        //Add to menu configuration
        $doctrine = $this->getContainer()->get('doctrine');
        $namespace = $doctrine->getAliasNamespace();
        
        $questionHelper = $this->getQuestionHelper();
        
        $defaultAnswer = array('yes');

        
        $question = new ConfirmationQuestion($questionHelper->getQuestion('Would you like to add it to the admin menu', $input->getOption('add-to-menu') ? 'yes' : 'no', '?'), $input->getOption('add-to-menu')); 
        
        
        $add = $questionHelper->ask($input, $output, $question);
        
        
        if ($add == true)
        {
            $defaultAnswer = array(str_replace('\\', '/', $entity));
            
            $question = new Question($questionHelper->getQuestion('Wich beautiful name would you like to give it', $input->getOption('beauty-name')), $input->getOption('beauty-name'));
            $question->setAutocompleterValues($defaultAnswer);
            
            $beautyName = $questionHelper->ask($input, $output, $question);
            
            
            $loader = new \Symfony\Component\Yaml\Yaml();
            
            $config = $loader->parse(__DIR__.'/../../../../../../app/config/config.yml');
            $menus = $config['skcms_admin']['menuGroups'];
            $possibleAnswers = [];
            foreach ($menus as $menuName => $menu)
            {
                $possibleAnswers[] = $menuName;
            }
            $question = new Question($questionHelper->getQuestion('In wich menu would you like to diplay it ? ('.implode(',',$possibleAnswers).')', $input->getOption('menu-group')), $input->getOption('menu-group'));
            
            $menuNameChoosed = $questionHelper->ask($input,$output,$question);
            
            if (array_key_exists($menuNameChoosed, $config['skcms_admin']['menuGroups']));
            {
                $menuIdChoosed = $config['skcms_admin']['menuGroups'][$menuNameChoosed]['id'];
            }
            
            dump($bundle->getContainer());
            dump($entity);
            die();
            
            
//            $entity = 
//                    [
//                      str_replace('\\', '/', $entity)=>
//                        [
//                            'name'=> str_replace('\\', '/', $entity),
//                            'beautyName'=> $beautyName,
//                            'bundle': $bundle->getName(),
//                            
//            
//            class: \BES\CoreBundle\Entity\Page
//            form: \BES\CoreBundle\Form\PageType
//            menuGroup: 1
//            listProperties: 
//                name:
//                    dataName: 'title'
//                    beautyName: 'Titre'
//                    type: 'string'  
//                    ];
            
            
            
            
        }
        
        
        
        
        
    }
}
