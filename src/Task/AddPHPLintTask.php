<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Task;

use Suilven\PHPTravisEnhancer\Abstraction\TaskBase;
use Suilven\PHPTravisEnhancer\IFace\Task;

class AddPHPLintTask extends TaskBase implements Task
{
    /** @return string bash variable for use in Travis script */
    public function getFlag(): string
    {
        return 'LINT_CHECK';
    }


    public function getCommand(): string
    {
        return 'lint';
    }


    public function getTravisBeforeScript(): ?string
    {
        return null;
    }


    public function getTravisScript(): ?string
    {
        return 'vendor/bin/parallel-lint src/ tests/';
    }


    /** @return array<string,string> */
    public function getComposerScripts(): array
    {
        return ["lint" => "vendor/bin/parallel-lint src/ tests/",];
    }


    /** @return array<string> */
    public function getComposerPackages(): array
    {
        return ['php-parallel-lint/php-parallel-lint', 'php-parallel-lint/php-console-highlighter'];
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
