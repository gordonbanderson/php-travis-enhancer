<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Tests;

use PHPUnit\Framework\TestCase;

class DeleteFileIfExistsTest extends TestCase
{

    public function testRemoveIfExists(): void
    {
        $filename = '.travis-to-rm';
        \file_put_contents($filename, 'some text');
        $this->removeIfExists($filename);
        $this->assertFalse(\file_exists($filename));
    }


    /**
     * Remove a file if it exists in the root directory of the project. Used for testing different Travis scenarios,
     * and then clean up
     *
     * @param string $filename the name of the file, without directory, to remove if it exists
     */
    protected function removeIfExists(string $filename): void
    {
        $path = \getcwd() . '/' . $filename;
        \error_log('CHECKING PATH: ' . $path);
        if (!\file_exists($path)) {
            return;
        }

        \error_log('  ^^ PATH EXISTS, DELETING IT');
        \unlink($path);
    }


    /**
     * Copy the sample Travis file to $filename in the root of the project
     *
     * @param string $filename the name of the file to copy to, without directory path
     */
    protected function copySampleTravisFileTo(string $filename): void
    {
        \copy(\getcwd() . '/tests/files/.travis.yml', \getcwd() . '/' . $filename);
    }
}
