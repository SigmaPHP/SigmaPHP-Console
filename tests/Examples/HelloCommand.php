<?php

namespace SigmaPHP\Console\Tests\Examples;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\Option;

/**
 * Hello Class.
 */
class HelloCommand extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('hello');
        $this->setDescription('say hello to user');

        $this->addArgument('name', 'User\'s name that we want to greet');

        $this->addOption('greeting', 'g',
            'Greeting verb like hi, hello..etc', Option::PARAMETER_REQUIRED);
        $this->addOption('title', 't',
            'User\'s title like Mr., Ms. ..etc', Option::PARAMETER_REQUIRED);
    }

    /**
     * Execute.
     *`
     * @return void
     */
    public function execute()
    {
        // $buffer = 'Hello ';

        // if ($this->hasOption('greeting')) {
        //     $buffer = $this->getOption('greeting') . ' ';
        // }

        // if ($this->hasOption('title')) {
        //     $buffer .= $this->getOption('title') . ' ';
        // }

        // if ($this->hasArgument('name')) {
        //     $buffer .= $this->getArgument('name');
        // }

        // echo $buffer . PHP_EOL;

        // $this->table->create(
        //     [
        //         'full_name',
        //         'age',
        //         'email_address',
        //         'phone_number',
        //         'country',
        //         'occupation',
        //         'registration_date',
        //     ],
        //     [
        //         [
        //             'Ahmed Mohamed Abdelrahman',
        //             '35',
        //             'ahmed.mohamed.abdelrahman@example.com',
        //             '+971501234567',
        //             'United Arab Emirates',
        //             'Senior Software Engineer',
        //             '2026-01-15',
        //         ],
        //         [
        //             'Ali Hassan Mahmoud El-Sayed',
        //             '29',
        //             'ali.hassan.mahmoud@example.com',
        //             '+971502345678',
        //             'Egypt',
        //             'Backend Software Developer',
        //             '2026-02-21',
        //         ],
        //         [
        //             'Mohamed Ibrahim Abdelaziz',
        //             '42',
        //             'mohamed.ibrahim.abdelaziz@example.com',
        //             '+971503456789',
        //             'Saudi Arabia',
        //             'Solutions Architect',
        //             '2026-03-08',
        //         ],
        //         [
        //             'Omar Khaled Mostafa',
        //             '31',
        //             'omar.khaled.mostafa@example.com',
        //             '+971504567890',
        //             'Jordan',
        //             'DevOps Engineer',
        //             '2026-04-17',
        //         ],
        //         [
        //             'Youssef Ahmed Mahmoud',
        //             '27',
        //             'youssef.ahmed.mahmoud@example.com',
        //             '+971505678901',
        //             'Kuwait',
        //             'Full Stack Developer',
        //             '2026-05-03',
        //         ],
        //     ]
        // );

        $this->table->create(
            ['id', 'title', 'description'],
            [
                [
                    1,
                    'Introduction to PHP',
                    "This is the first line of the description.\nThis is the second line of the description.",
                ],
                [
                    2,
                    'Working with Arrays',
                    "Arrays are one of the most commonly used data structures in PHP.\nThey can contain strings, numbers, objects, and other arrays.",
                ],
                [
                    3,
                    'Object Oriented Programming',
                    "PHP provides powerful object-oriented programming features.\nClasses can contain properties, methods, constants, and more.",
                ],
                [
                    4,
                    'Database Configuration',
                    "Configure your database connection using the application configuration.\nYou can then use the connection throughout your application.",
                ],
                [
                    5,
                    'Error Handling',
                    "Proper error handling makes applications easier to debug and maintain.\nExceptions can be caught and handled using try and catch blocks.",
                ],
            ]
        );
    }
}

