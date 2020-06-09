<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Task;

use Suilven\PHPTravisEnhancer\Abstraction\TaskBase;
use Suilven\PHPTravisEnhancer\IFace\Task;

class AddPHPStanTask extends TaskBase implements Task
{
    /** @return string bash variable for use in Travis script */
    public function getFlag(): string
    {
        return 'PHPSTAN_TEST';
    }

    public function getCommand(): string {
        return 'phpstan';
    }



    public function getBeforeScript(): ?string
    {
        return null;
    }


    public function getScript(): ?string
    {
        return 'vendor/bin/phpstan analyse --level=6 -c tests/phpstan.neon src/;';
    }


    /** @return array<string> */
    public function getComposerPackages(): array
    {
        return ['phpstan/phpstan-strict-rules', 'phpstan/extension-installer'];
    }


    /** @return array<string, string> */
    public function filesToCopy(): array
    {
        return ['files/phpstan.neon' => 'TESTS_DIR/phpstan.neon'];
    }
}
