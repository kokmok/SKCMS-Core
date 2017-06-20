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
        $message = new \Swift_Message('Hello Email');
        $message->setFrom($this->getContainer()->getParameter('mailer_user'))
            ->setTo($input->getArgument(self::ARGUMENT_EMAIL))
            ->setBody('This is just there to validate skcms email sending feature');
        
        
        $result = $this->getContainer()->get('mailer')->send($message);
        
        $output->writeLn('sending message to '.$input->getArgument(self::ARGUMENT_EMAIL).' with result '.$result);
        
    }
}
