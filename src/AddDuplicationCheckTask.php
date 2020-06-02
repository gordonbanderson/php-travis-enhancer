<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer;

use Suilven\PHPTravisEnhancer\Helper\TravisYMLHelper;

class AddDuplicationCheckTask
{

    /**
     * Update Travis file to incorporate a check for duplicate code
     *
     * @param string $travisFile An injectable filename (for testing), leave blank for default of .travis.yml
     */
    public function run(string $travisFile = '.travis.yml'): void
    {
        $helper = new TravisYMLHelper($travisFile);
        $yaml = $helper->loadTravis();
        \error_log(\print_r($yaml, 1));
    }

}