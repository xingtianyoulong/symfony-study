<?php

namespace Jootop\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument; 
use Symfony\Component\Console\Output\OutputInterface;
 
class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            // 命令的名字（"bin/console" 后面的部分）
            ->setName('app:create-users')
     
            // the short description shown while running "php bin/console list"
            // 运行 "php bin/console list" 时的简短描述
            ->setDescription('Creates new users.')
     
            // the full command description shown when running the command with
            // the "--help" option
            // 运行命令时使用 "--help" 选项时的完整命令描述
            ->setHelp("This command allows you to create users...")

            // configure an argument / 配置一个参数
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
        ;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        // 输出多行到控制台（在每一行的末尾添加 "\n"）
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);
     
        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');
     
        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user ');

        // retrieve the argument value using getArgument()
        // 使用 getArgument() 取出参数值
        $output->writeln('Username: '.$input->getArgument('username'));
    }
}