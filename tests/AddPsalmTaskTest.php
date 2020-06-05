<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use PHPUnit\Framework\TestCase;
use Suilven\PHPTravisEnhancer\AddPHPLintTask;
use Suilven\PHPTravisEnhancer\AddPsalmTask;

class AddPsalmTaskTest extends TestCase
{

    use CommonTestingMethods;

    private const TRAVIS_FILE = '.travis-psalm.yml';

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


    public function testPsalmEmptyTravisFile(): void
    {
        $task = new AddPsalmTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testAddPsalmEmptyTravisFile.yml');
    }


    public function testPsalmExistingTravisFile(): void
    {
        $this->copySampleTravisFileTo(self::TRAVIS_FILE);
        $task = new AddPsalmTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testAddPsalmExistingTravisFile.yml');
    }
}
