<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use PHPUnit\Framework\TestCase;
use Suilven\PHPTravisEnhancer\AddPHPLintTask;

class AddPHPLinkTaskTest extends TestCase
{

    use CommonTestingMethods;

    private const TRAVIS_FILE = '.travis-phplint.yml';

    public function setUp(): void
    {
        parent::setUp();

        $this->removeIfExists(self::TRAVIS_FILE);
    }


    public function tearDown(): void
    {
        parent::tearDown();

        $this->removeIfExists(self::TRAVIS_FILE);
    }


    public function testPHPLintEmptyTravisFile(): void
    {
        $task = new AddPHPLintTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testAddPhpLintEmptyTravisFile.yml');
    }


    public function testPHPLintExistingTravisFile(): void
    {
        $this->copySampleTravisFileTo(self::TRAVIS_FILE);
        $task = new AddPHPLintTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testAddPhpLintExistingTravisFile.yml');
    }
}
