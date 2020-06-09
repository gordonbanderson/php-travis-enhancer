<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\IFace;

interface Task
{
    /** @return string a flag, such as PHP_STAN or DUPLICATE_CHECK */
    public function getFlag(): string;


    /** @return string|null bash code to go in the before_script section, null if not required */
    public function getBeforeScript(): ?string;


    /** @return string|null bash code to go in the script section, null if not required */
    public function getScript(): ?string;

    /** @return array<string> list of composer packages to install */
    public function getComposerPackages(): array

    /**
     * Execute the task of altering the Travis file
     *
     * @param string $travisFile The travis file to alter. This is overridden for testing purposes
     */
    public function run(string $travisFile = '.travis.yml'): void;
}
