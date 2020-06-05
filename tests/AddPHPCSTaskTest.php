<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use PHPUnit\Framework\TestCase;
use Suilven\PHPTravisEnhancer\AddPHPCSTask;

class AddPHPCSTaskTest extends TestCase
{

    use CommonTestingMethods;

    private const TRAVIS_FILE = '.travis-phpcs.yml';

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


    public function testPHPCSEmptyTravisFile(): void
    {
        $task = new AddPHPCSTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testAddPHPCSEmptyTravisFile.yml');
    }


    public function testPHPCSExistingTravisFile(): void
    {
        $this->copySampleTravisFileTo(self::TRAVIS_FILE);
        $task = new AddPHPCSTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testAddPHPCSExistingTravisFile.yml');
    }
}
