<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer;

use Suilven\PHPTravisEnhancer\Helper\TravisYMLHelper;

class AddDuplicationCheckTask
{

    public function run($travisFile = '.travis.yml'): void
    {
        $helper = new TravisYMLHelper($travisFile);
        $yaml = $helper->loadTravis();
        \error_log(\print_r($yaml, 1));
    }

}