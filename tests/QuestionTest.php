<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Question;
use SigmaPHP\Console\IO;

/**
 * Question Test.
 */
class QuestionTest extends TestCase
{
    /**
     * @var IO $io
     */
    private $io;

    /**
     * @var resource $testStream
     */
    private $testStream;

    /**
     * @var Question $question
     */
    private $question;

    /**
     * QuestionTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->io = new IO();

        touch('tests/fake_stream');

        $this->testStream = fopen('tests/fake_stream', 'r+');

        $this->question = new Question();

        $this->question->setIOHandler($this->io);
    }

    /**
     * QuestionTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Inject value into input stream.
    *
    * @param string $value
    * @return void
    */
    private function injectInput($value)
    {
        // reset stream
        ftruncate($this->testStream, 0);
        rewind($this->testStream);

        $this->io->setInputStream($this->testStream);

        fwrite($this->testStream, $value);
        rewind($this->testStream);
    }

    /**
     * Test confirmation.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testConfirmation()
    {
        $this->injectInput('YEs');

        $input = $this->question->confirmation("Please confirm?");

        $this->assertTrue($input);
    }

    /**
     * Test will throw exception if confirmation is not yes or no.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfConfirmationIsNotYesOrNo()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->injectInput('Unknown');

        $input = $this->question->confirmation("Please confirm?");

        $this->assertTrue($input);
    }

    /**
     * Test asking.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAsking()
    {
        $this->injectInput('Ahmed');

        $input = $this->question->ask("What's your name?");

        $this->assertEquals('Ahmed', $input);
    }

    /**
     * Test choice.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testChoice()
    {
        $this->injectInput('blue');

        $input = $this->question->choice("What's your favorite color?",
            ['red', 'blue', 'green']
        );

        $this->assertEquals('blue', $input);
    }

    /**
     * Test will throw exception if choice is undefined.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfChoiceIsUndefined()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->injectInput('Unknown');

        $this->question->choice("What's your favorite color?",
            ['red', 'blue', 'green']
        );
    }
}
