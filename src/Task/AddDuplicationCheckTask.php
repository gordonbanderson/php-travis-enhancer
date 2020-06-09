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


    public function getBeforeScript(): ?string
    {
        return 'sudo apt remove -y nodejs && curl '
            . '-sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && sudo bash nodesource_setup.sh '
            . '&& sudo apt install -y build-essential nodejs && which npm && npm install jscpd@3.2.1';
    }


    public function getScript(): ?string
    {
        return 'node_modules/jscpd/bin/jscpd src && node_modules/jscpd/bin/jscpd tests';
    }


    public function getComposerPackages()
    {
        return [];
    }
}
