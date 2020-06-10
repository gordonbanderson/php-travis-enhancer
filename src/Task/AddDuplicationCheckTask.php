<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Task;

use Suilven\PHPTravisEnhancer\Abstraction\TaskBase;
use Suilven\PHPTravisEnhancer\IFace\Task;

class AddDuplicationCheckTask extends TaskBase implements Task
{
    /** @return string bash variable for use in Travis script */
    public function getFlag(): string
    {
        return 'DUPLICATE_CODE_CHECK';
    }


    public function getCommand(): string
    {
        return 'duplication';
    }


    public function getTravisBeforeScript(): ?string
    {
        return 'sudo apt remove -y nodejs && curl '
            . '-sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && sudo bash nodesource_setup.sh '
            . '&& sudo apt install -y build-essential nodejs && which npm && npm install jscpd@3.2.1';
    }


    public function getTravisScript(): ?string
    {
        return 'node_modules/jscpd/bin/jscpd src && node_modules/jscpd/bin/jscpd tests';
    }


    /** @return array<string,string> this command is only run within Travis, as it requires node installed */
    public function getComposerScripts(): array
    {
        return [];
    }


    /** @return array<string> */
    public function getComposerPackages(): array
    {
        return [];
    }


    /** @return array<string, string> */
    public function filesToCopy(): array
    {
        return [];
    }


    public function isCodeCheck(): bool
    {
        return false;
    }
}
