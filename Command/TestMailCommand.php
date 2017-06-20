<?php
namespace SKCMS\CoreBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class TestMailCommand extends ContainerAwareCommand
{
    const ARGUMENT_EMAIL = "ARGUMENT_EMAIL";
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('skcms_core:test_mail')
            ->addArgument(self::ARGUMENT_EMAIL,InputArgument::OPTIONAL,'',"jona@solid-kiss.be")
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $this->getContainer()->get('mailer')->createMessage();
        
    }
}
