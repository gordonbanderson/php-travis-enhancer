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


    public function getBeforeScript(): ?string
    {
        return null;
    }


    public function getScript(): ?string
    {
        return 'vendor/bin/phpstan analyse --level=6 -c tests/phpstan.neon src/;';
    }


    public function getComposerPackages()
    {
        return ['phpstan/phpstan-strict-rules', 'phpstan/extension-installer'];
    }

    // @todo Copy the phpstan.neon config file
}
