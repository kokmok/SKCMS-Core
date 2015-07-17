<?php
namespace SKCMS\CoreBundle\Command;

//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Input\InputArgument;
//use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Input\InputOption;
//use Symfony\Component\Console\Output\OutputInterface;
//use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\HttpKernel\KernelInterface;
use Sensio\Bundle\GeneratorBundle\Generator\BundleGenerator;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
/**
 * Description of SkizeEntity
 *
 * @author Jona
 */
class InstallCommand extends \Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand
{
 
    private $config;
    private $skConfig;
    private $routing;
    private $skRouting;
    private $loader;
    private $module_installed;
    const CONFIG_PATH = '/../../../../../../app/config/config.yml';
    const SECURITY_PATH = '/../../../../../../app/config/security.yml';
    const ROUTING_PATH = '/../../../../../../app/config/routing.yml';

    public function __construct($name = null) {
        parent::__construct($name);
        $this->skConfig = [];
        $this->config = [];
        $this->routing = [];
        $this->skRouting = [];
    }

    protected function configure()
    {
        $this
            ->setName('skcms:install')
            ->setDescription('Install SKCMS, update and backup config.yml, security.yml, routing.yml and AppKernel.php')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $errors = array();
        $runner = $questionHelper->getRunner($output, $errors);
        
        $this->loader = new \Symfony\Component\Yaml\Yaml();
        $this->config = $this->loader->parse(__DIR__.self::CONFIG_PATH);
        
        $this->loadTranslator();
        
        
        
        $runner($this->loopBundle($questionHelper,$input,$output));
        
        
       
        
        $this->updateConfiguration();
 
                
        
    }
    
    private function loopBundle(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output){
        $bundles = [
            'admin' => [
                'kernel'=>[
                    ['bundle'=>'SKCMSAdminBundle','namespace'=>'SKCMS\AdminBundle'],
                    ['bundle'=>'SKCMSCKFinderBundle','namespace'=>'SKCMS\CKFinderBundle'],
                    ['bundle'=>'IvoryCKEditorBundle','namespace'=>'Ivory\CKEditorBundle'],
                    ['bundle'=>'JonlilCKFinderBundle','namespace'=>'Jonlil\CKFinderBundle'],
                    ['bundle'=>'StofDoctrineExtensionsBundle','namespace'=>'Stof\DoctrineExtensionsBundle'],
                ],
                'config'=>['loadSkCMSAdmin','loadCKEditor','loadDoctrineFunctions'],
                'route'=>['routeAdmin']
                
            ],
            
            'user' =>
            [
                'kernel'=>[
                    ['bundle'=>'SKCMSUserBundle','namespace'=>'SKCMS\UserBundle'],
                    ['bundle'=>'FOSUserBundle','namespace'=>'FOS\UserBundle']
                ],
                'config'=>['loadFosUser','setSecurity'],
                'route'=>['routeUser']
            ],
            'contact'=>
            [
                'kernel'=>[['bundle'=>'SKCMSContactBundle','namespace'=>'SKCMS\ContactBundle']],
                'config'=>['loadSKContact'],
                'route'=>['routeContact']
            ],
            'tracking'=>
            [
                'kernel'=>[['bundle'=>'SKCMSTrackingBundle','namespace'=>'SKCMS\TrackingBundle']],
                'route'=>['routeTracking']
            ],
            'shop'=>
            [
                'kernel'=>[['bundle'=>'SKCMSShopBundle','namespace'=>'SKCMS\ShopBundle']],
                'route'=>['routeShop']
            ],
            'front'=>
            [
                'kernel'=>[['bundle'=>'SKCMSFrontBundle','namespace'=>'SKCMS\FrontBundle']],
                'route'=>['routeFront']
            ],
        ];
        foreach ($bundles as $bundleName => $bundle)
        {
            if ($input->isInteractive()) {
                $question = new ConfirmationQuestion($questionHelper->getQuestion('Install '.$bundleName.' (kernel + config)', 'yes', '?'), true);
                $auto = $questionHelper->ask($input, $output, $question);
                $this->module_installed = $auto;
            }
            if ($auto)
            {
                foreach ($bundle['kernel'] as $bundleKernel)
                {
                    $kernelUpdate = $this->updateKernel($this->getContainer()->get('kernel'), $bundleKernel['namespace'], $bundleKernel['bundle']);
                }
                
                if (array_key_exists('config', $bundle) && is_array($bundle['config']))
                {
                    foreach ($bundle['config'] as $configMethod)
                    {
                        call_user_method($configMethod, $this);

                    }
                }
                if (array_key_exists('route', $bundle) && is_array($bundle['route']))
                {
                    foreach ($bundle['route'] as $routeMethod)
                    {
                        call_user_method($routeMethod, $this);

                    }
                }
                
            }
        }
    }
    
    
    protected function updateKernel( KernelInterface $kernel, $namespace, $bundle)
    {
        $auto = true;

//        $output->write('Enabling the bundle inside the Kernel: ');
        $manip = new KernelManipulator($kernel);
        try {
            $ret = $auto ? $manip->addBundle($namespace.'\\'.$bundle) : false;

            if (!$ret) {
                $reflected = new \ReflectionObject($kernel);

                return array(
                    sprintf('- Edit <comment>%s</comment>', $reflected->getFilename()),
                    '  and add the following bundle in the <comment>AppKernel::registerBundles()</comment> method:',
                    '',
                    sprintf('    <comment>new %s(),</comment>', $namespace.'\\'.$bundle),
                    '',
                );
            }
        } catch (\RuntimeException $e) {
            return array(
                sprintf('Bundle <comment>%s</comment> is already defined in <comment>AppKernel::registerBundles()</comment>.', $namespace.'\\'.$bundle),
                '',
            );
        }
    }
    
    private function setSecurity()
    {
        $SkSecurity = 
        [
            'security'=>
            [
                'encoders'=> ['FOS\UserBundle\Model\UserInterface' => 'sha512'],
                'role_hierarchy'=> 
                [
                    'ROLE_ADMIN'=> 'ROLE_USER',
                    'ROLE_SUPER_ADMIN'=> ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH']
                ],
                'providers'=>
                [
                    'fos_userbundle'=> 
                    [
                        'id'=> 'fos_user.user_provider.username'
                    ]
                ],
                'firewalls'=>
                [
                    'main'=>
                    [
                        'pattern'=> '^/',
                        'form_login'=> ['provider'=> 'fos_userbundle','csrf_provider'=> 'form.csrf_provider'],
                        'logout'=>       true,
                        'anonymous'=> true,

                    ]
                ],
                'access_control'=>
                [
                    [ 'path'=> '^/login$', 'role'=> 'IS_AUTHENTICATED_ANONYMOUSLY' ],
                    [ 'path'=> '^/login_check$', 'role'=> 'IS_AUTHENTICATED_ANONYMOUSLY' ],
                    ['path'=> '^/register', 'role'=> 'IS_AUTHENTICATED_ANONYMOUSLY' ],
                    ['path'=> '^/resetting', 'role'=> 'IS_AUTHENTICATED_ANONYMOUSLY '],
                    ['path' => '^/admin/templates', 'role'=> 'ROLE_SUPER_ADMIN' ],
                    ['path'=> '^/admin/entity-list', 'role'=> 'ROLE_SUPER_ADMIN '],
                    ['path'=> '^/admin/', 'role'=> 'ROLE_ADMIN' ],
                    ['path'=> '^/', 'role'=> 'IS_AUTHENTICATED_ANONYMOUSLY' ]
                ]
            ]
        ];
        $originalSecurity = $this->loader->parse(__DIR__.self::SECURITY_PATH);
        file_put_contents(__DIR__.self::SECURITY_PATH.'.bkp', $this->loader->dump($originalSecurity,3));
        
        $finalSecurity = array_replace_recursive($originalSecurity,$SkSecurity);
        file_put_contents(__DIR__.self::SECURITY_PATH, $this->loader->dump($finalSecurity,3));
        
            
            
    }
    
    private function loadSkCMSAdmin()
    {
        $this->skConfig['skcms_admin'] =
        [
           'modules'=>
            [
                'user'=> ['enabled'=> true, 'userEntity'=> null],
                'contact'=> ['enabled'=> false] 
            ],
            'siteInfo'=> ['homeRoute'=> "skcms_front_home",'locales'=> ['fr']],
            'menuGroups'=>
            [
                'group1'=>['id'=> 1,'name'=> 'Documents']
            ],
            'entities'=>[]
            
    
        
        ];
        $this->skConfig['assetic']['bundles'] = ['SKCMSAdminBundle'];
        
            
    
    }
    
    private function loadSkContact()
    {
        $this->skConfig['skcms_contact'] =
            [
                'entity'=>'',
                'form_type'=>'',
                'email_notification'=>
                [
                    'subject'=>'New message on website',
                    'target'=>[]
                ]
                
            ]
            
        ;
    }
    
    
    private function loadFosUser()
    {
        $this->skConfig['fos_user'] = ['db_driver'=>'orm','firewall_name'=>'main','user_class'=>'SKCMS\UserBundle\Entity\User'];
    }
    
    public function loadCKEditor()
    {
        
        $this->skConfig['jonlil_ck_finder'] = ['license'=>['key'=>'','name'=>''],'baseDir'=>"%assetic.read_from%",'baseUrl'=>"/uploads/",'service'=>'php'];
        $this->skConfig['skcmsck_finder'] = ['license'=>['key'=>'','name'=>''],'baseDir'=>"%assetic.read_from%",'baseUrl'=>"/uploads/",'service'=>'php'];
        $this->skConfig['parameters']=['jonlil.ckfinder.customAuthentication'=>'%kernel.root_dir%/...path your custom config.php or any other file'];
               
        
    }
    
    private function mergeOrCreate($key,$array)
    {
        if (array_key_exists($key, $this->config))
        {
            $this->config[$key] = array_replace_recursive($this->config[$key],$array);
        }
        else
        {
            $this->config[$key] = $array;
        }
    }
    private function setStof()
    {
        $this->skConfig['stof_doctrine_extensions']['orm']['default']['sluggable'] = true;
//        $stof = ['orm'=>['default'=>['sluggable'=>true,'translatable'=>true]]];
//        $this->mergeOrCreate('stof_doctrine_extensions', $stof);
    }
    
    private function loadDoctrineFunctions()
    {
        $skConfig['doctrine'] = ['orm'=>['dql'=>['numeric_function  s'=>['Rand'=>'SKCMS\CoreBundle\DoctrineFunctions\Rand']]]];

    }
    
    private function updateConfiguration()
    {
        //Backing up old config
        $yaml = $this->loader->dump($this->config,2);
        file_put_contents(__DIR__.self::CONFIG_PATH.'.bkp', $yaml);
        
        //Update new config
        $newConfig = array_replace_recursive($this->config,$this->skConfig);
        $yaml = $this->loader->dump($newConfig,3);
        file_put_contents(__DIR__.self::CONFIG_PATH, $yaml);
        
        //Backing up old routing
        $this->routing = $this->loader->parse(__DIR__.self::ROUTING_PATH);
        $yaml = $this->loader->dump($this->routing,2);
        file_put_contents(__DIR__.self::ROUTING_PATH.'.bkp', $yaml);
        
        //Update new config
        $newRouting = array_replace_recursive($this->routing,$this->skRouting);
        $yaml = $this->loader->dump($newRouting,3);
        file_put_contents(__DIR__.self::ROUTING_PATH, $yaml);
    }
    
    private function loadTranslator()
    {
        $this->skConfig['framework'] = ['translator'=>['fallbacks' => ['%locale%']]];
//        if (array_key_exists('translator', $this->config['framework']) && array_key_exists('fallbacks',$this->config['framework']['translator']))
//        {
//            $this->config['framework']['translator']['fallbacks'] = ['%locale%'];
//        }
//        else
//        {
//            $this->config['framework']['translator'] = ['fallbacks'=>['%locale%']];
//        }
        
    }
    
    
    
    /** ROUTING FUNCTIONS */
    
    private function routerouteShop()
    {
         $this->skRouting['skcms_shop']=
                [
                    'resource'=>"@SKCMSShopBundle/Resources/config/routing.yml",
                    'prefix'=> '/'
                ];
    }
    
    private function routeTracking()
    {
         $this->skRouting['skcms_tracking']=
                [
                    'resource'=>"@SKCMSTrackingBundle/Resources/config/routing.yml",
                    'prefix'=> '/'
                ];
    }
    private function routeUser()
    {
         $this->skRouting['fos_user_security']=
                [
                    'resource'=>"@FOSUserBundle/Resources/config/routing/security.xml",
                    'prefix'=> '/'
                ];
         $this->skRouting['fos_user_profile']=
                [
                    'resource'=>"@FOSUserBundle/Resources/config/routing/profile.xml",
                    'prefix'=> '/profile'
                ];
         $this->skRouting['fos_user_resetting']=
                [
                    'resource'=>"@FOSUserBundle/Resources/config/routing/resetting.xml",
                    'prefix'=> '/resetting'
                ];
         $this->skRouting['fos_user_change_password']=
                [
                    'resource'=>"@FOSUserBundle/Resources/config/routing/change_password.xml",
                    'prefix'=> '/profile'
                ];
    }
    private function routeFront()
    {
        $this->skRouting['skcms_front']=
                [
                    'resource'=>"@SKCMSFrontBundle/Resources/config/routing.yml",
                    'prefix'=> '/'
                ];
        
    }
    private function routeContact()
    {
        $this->skRouting['skcms_contact']=
                [
                    'resource'=>"@SKCMSContactBundle/Resources/config/routing-skcmsContact.yml",
                    'prefix'=> '/'
                ];
                
    }
    private function routeAdmin()
    {
        $this->skRouting['skcmsck_finder']=
                [
                    'resource'=>"@SKCMSCKFinderBundle/Resources/config/routing/routing.yml",
                    'prefix'=> '/'
                ];
        $this->skRouting['skcms_admin']=
                [
                    'resource'=>"@SKCMSAdminBundle/Resources/config/routing.yml",
                    'prefix'=> '/admin'
                ];
        $this->skRouting['ck_finder']=
                [
                    'resource'=>"@JonlilCKFinderBundle/Resources/config/routing/routing.yml",
                    'prefix'=> '/ckfinder'
                ];
        
    }
    
    
    protected function createGenerator()
    {
        return null;
     //   return new BundleGenerator($this->getContainer()->get('filesystem'));
    }
    
    
    
}



//$manager = new DisconnectedMetadataFactory($this->getContainer()->get('doctrine'));