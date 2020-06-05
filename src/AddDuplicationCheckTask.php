<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer;

use Suilven\PHPTravisEnhancer\Abstraction\AbstractTask;
use Suilven\PHPTravisEnhancer\Helper\TravisYMLHelper;
use Suilven\PHPTravisEnhancer\IFace\TaskInterface;

class AddDuplicationCheckTask extends AbstractTask implements TaskInterface
{
    public function getFlag()
    {
        return 'DUPLICATE_CODE_CHECK';
    }


    public function getBeforeScript()
    {
        return 'sudo apt remove -y nodejs && curl '
            . '-sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && sudo bash nodesource_setup.sh '
            . '&& sudo apt install -y build-essential nodejs && which npm && npm install jscpd@3.2.1';
    }


    public function getScript()
    {
       return 'node_modules/jscpd/bin/jscpd src && node_modules/jscpd/bin/jscpd tests';
    }
}
