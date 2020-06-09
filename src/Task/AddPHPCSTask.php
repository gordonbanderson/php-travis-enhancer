<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Task;

use Suilven\PHPTravisEnhancer\Abstraction\TaskBase;
use Suilven\PHPTravisEnhancer\IFace\Task;

class AddPHPCSTask extends TaskBase implements Task
{
    /** @return string bash variable for use in Travis script */
    public function getFlag(): string
    {
        return 'PHPCS_TEST';
    }


    public function getBeforeScript(): ?string
    {
        return null;
    }


    public function getScript(): ?string
    {
        return 'vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests';
    }


    public function getComposerPackages()
    {
        return ['slevomat/coding-standard'];
    }

    // @todo Copy the ruleset.xml file
}
