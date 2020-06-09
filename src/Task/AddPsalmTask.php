<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Task;

use Suilven\PHPTravisEnhancer\Abstraction\TaskBase;
use Suilven\PHPTravisEnhancer\IFace\Task;

class AddPsalmTask extends TaskBase implements Task
{
    /** @return string bash variable for use in Travis script */
    public function getFlag(): string
    {
        return 'PSALM_TEST';
    }


    public function getBeforeScript(): ?string
    {
        return null;
    }


    public function getScript(): ?string
    {
        return './vendor/bin/psalm --show-info=true;';
    }


    public function getComposerPackages(): array
    {
        return ['vimeo/psalm'];
    }


    public function filesToCopy(): array
    {
        return [];
    }
}
