<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use Suilven\PHPTravisEnhancer\AddDuplicationCheckTask;

class DuplicateCodeTest extends DeleteFileIfExistsTest
{

    private const TRAVIS_FILE = '.travis-duplicate.yml';

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

    public function testDuplicationEmptyTravisFile(): void
    {
        $task = new AddDuplicationCheckTask();
        $task->run(self::TRAVIS_FILE);
    }

    public function testDuplicationExistingTravisFile(): void
    {
        $this->copySampleTravisFileTo(self::TRAVIS_FILE);
        $task = new AddDuplicationCheckTask();
        $task->run(self::TRAVIS_FILE);
    }

}