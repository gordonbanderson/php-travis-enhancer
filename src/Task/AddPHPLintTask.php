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


    public function getBeforeScript(): ?string
    {
        return null;
    }


    public function getScript(): ?string
    {
        return 'vendor/bin/parallel-lint src/ tests/';
    }


    public function getComposerPackages(): array
    {
        return ['php-parallel-lint/php-parallel-lint', 'php-parallel-lint/php-console-highlighter'];
    }


    public function filesToCopy(): array
    {
        return [];
    }
}
