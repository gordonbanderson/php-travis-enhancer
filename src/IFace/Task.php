<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\IFace;

interface Task
{
    /** @return string a flag, such as PHP_STAN or DUPLICATE_CHECK */
    public function getFlag(): string;


    /** @return string the command line name of the task, such as phpcs or lint */
    public function getCommand(): string;


    /** @return string|null bash code to go in the before_script section, null if not required */
    public function getTravisBeforeScript(): ?string;


    /** @return string|null bash code to go in the script section, null if not required */
    public function getTravisScript(): ?string;


    /** @return array<string,string> names of composer script command mapped to the bash code to execute. */
    public function getComposerScripts(): array;


    /** @return array<string> list of composer packages to install */
    public function getComposerPackages(): array;


    /** @return array<string, string> associative array of src -> dest */
    public function filesToCopy(): array;


    /** @return bool true if this task is to be added to the composer codeCheck script command */
    public function isCodeCheck(): bool;


    /**
     * Execute the task of altering the Travis file
     *
     * @param string $travisFile The travis file to alter. This is overridden for testing purposes
     */
    public function run(string $travisFile = '.travis.yml'): int;
}
