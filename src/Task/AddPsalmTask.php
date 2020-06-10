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


    public function getCommand(): string
    {
        return 'psalm';
    }


    public function getTravisBeforeScript(): ?string
    {
        return null;
    }


    public function getTravisScript(): ?string
    {
        return './vendor/bin/psalm --show-info=true;';
    }


    /** @return array<string,string> */
    public function getComposerScripts(): array
    {
        return ["psalm" =>"vendor/bin/psalm --show-info=true"];
    }


    /** @return array<string> */
    public function getComposerPackages(): array
    {
        return ['vimeo/psalm'];
    }


    /** @return array<string, string> */
    public function filesToCopy(): array
    {
        return [];
    }


    public function isCodeCheck(): bool
    {
        return true;
    }
}
