<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use Suilven\PHPTravisEnhancer\AddDuplicationCheckTask;

class AddPHPLinkTaskTest extends DeleteFileIfExistsTest
{

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
        $task = new AddDuplicationCheckTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testDuplicationEmptyTravisFile.yml');
    }


    public function testPHPLintExistingTravisFile(): void
    {
        $this->copySampleTravisFileTo(self::TRAVIS_FILE);
        $task = new AddDuplicationCheckTask();
        $task->run(self::TRAVIS_FILE);
        $this->assertExpectedFileContents(self::TRAVIS_FILE, 'testDuplicationExistingTravisFile.yml');
    }
}
