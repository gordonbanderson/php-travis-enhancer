<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use PHPUnit\Framework\TestCase;

class DeleteFileIfExistsTest extends TestCase
{

    const TRAVIS_FILE = '.travis-duplicate.yml';

    public function testRemoveIfExists(): void
    {
        $filename = '.travis-to-rm';
        \file_put_contents($filename, 'some text');
        $this->removeIfExists($filename);
        $this->assertFalse(\file_exists($filename));
    }

    protected function removeIfExists($filename): void
    {
        $path = \getcwd() . '/' . $filename;
        \error_log('CHECKING PATH: ' . $path);
        if (!\file_exists($path)) {
            return;
        }

        \error_log('  ^^ PATH EXISTS, DELETING IT');
        \unlink($path);
    }

    protected function copySampleTravisFileTo($filename): void
    {
        \copy(\getcwd() . '/tests/files/.travis.yml', \getcwd() . '/' . $filename);
    }

}