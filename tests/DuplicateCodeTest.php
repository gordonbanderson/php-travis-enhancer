<?php


namespace Suilven\PHPTravisEnhancer\Tests;


use PHPUnit\Framework\TestCase;
use Suilven\PHPTravisEnhancer\AddDuplicationCheckTask;

class DuplicateCodeTest extends DeleteFileIfExistsTest
{
    const TRAVIS_FILE = '.travis-duplicate.yml';

    public function setUp()
    {
        parent::setUp();
        $this->removeIfExists(self::TRAVIS_FILE);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->removeIfExists(self::TRAVIS_FILE);

    }

    public function testDuplicationEmptyTravisFile()
    {
        $task = new AddDuplicationCheckTask();
        $task->run(self::TRAVIS_FILE);
    }

    public function testDuplicationExistingTravisFile()
    {
        $this->copySampleTravisFileTo(self::TRAVIS_FILE);
        $task = new AddDuplicationCheckTask();
        $task->run(self::TRAVIS_FILE);
    }
}