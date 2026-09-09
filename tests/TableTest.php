<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Table;
use SigmaPHP\Console\IO;

/**
 * Table Test.
 */
class TableTest extends TestCase
{
    /**
     * @var IO $io
     */
    private $io;

    /**
     * @var Table $table
     */
    private $table;

    /**
     * TableTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->io = new IO();
        $this->io->setOutputStream(fopen('php://output', 'r+'));

        $this->table = new Table();
        $this->table->setIOHandler($this->io);
    }

    /**
     * TableTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test create a new table.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateNewTable()
    {
        $this->expectOutputString(file_get_contents(__DIR__ . '/table_output'));

        $this->table->create(
            [
                'Full Name',
                'Age',
                'Email Address',
                'Phone Number',
                'Country',
                'Occupation',
                'Registration Date',
            ],
            [
                [
                    'Ahmed Mohamed Abdelrahman',
                    '35',
                    'ahmed.mohamed.abdelrahman@example.com',
                    '+971501234567',
                    'United Arab Emirates',
                    'Senior Software Engineer',
                    '2026-01-15',
                ],
                [
                    'Ali Hassan Mahmoud El-Sayed',
                    '29',
                    'ali.hassan.mahmoud@example.com',
                    '+971502345678',
                    'Egypt',
                    'Backend Software Developer',
                    '2026-02-21',
                ],
                [
                    'Mohamed Ibrahim Abdelaziz',
                    '42',
                    'mohamed.ibrahim.abdelaziz@example.com',
                    '+971503456789',
                    'Saudi Arabia',
                    'Solutions Architect',
                    '2026-03-08',
                ],
                [
                    'Omar Khaled Mostafa',
                    '31',
                    'omar.khaled.mostafa@example.com',
                    '+971504567890',
                    'Jordan',
                    'DevOps Engineer',
                    '2026-04-17',
                ],
                [
                    'Youssef Ahmed Mahmoud',
                    '27',
                    'youssef.ahmed.mahmoud@example.com',
                    '+971505678901',
                    'Kuwait',
                    'Full Stack Developer',
                    '2026-05-03',
                ],
            ]
        );
    }
}
