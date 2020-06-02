<?php


namespace Suilven\PHPTravisEnhancer\Tests;


use PHPUnit\Framework\TestCase;
use Suilven\PHPTravisEnhancer\AddDuplicationCheckTask;

class DeleteFileIfExistsTest extends TestCase
{
    const TRAVIS_FILE = '.travis-duplicate.yml';

    protected function removeIfExists($filename)
    {
        $path = getcwd() . '/' . $filename;
        error_log('CHECKING PATH: ' . $path);
        if (file_exists($path)) {
            error_log('  ^^ PATH EXISTS, DELETING IT');
            unlink($path);
        }
    }


    protected function copySampleTravisFileTo($filename)
    {
        copy(getcwd() . '/tests/files/.travis.yml', getcwd() . '/' . $filename);
    }


    public function testRemoveIfExists()
    {
        $filename = '.travis-to-rm';
        file_put_contents($filename, 'some text');
        $this->removeIfExists($filename);
        $this->assertFalse(file_exists($filename));
    }
}