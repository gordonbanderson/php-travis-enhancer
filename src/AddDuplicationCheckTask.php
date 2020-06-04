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
        $yamlAsArray = $helper->loadTravis();

        $helper->ensurePathExistsInYaml($yamlAsArray, 'matrix/include');

        if (!$helper->checkForExistingInEnv($yamlAsArray, 'DUPLICATE_CODE_CHECK')) {
            // add a matrix entry
            $yamlAsArray['matrix']['include'][] = [
                'php' => 7.4,
                'env' => 'DUPLICATE_CODE_CHECK=1',
            ];

            // install jdscpd, node tool, for duplication detection
            $helper->ensurePathExistsInYaml($yamlAsArray, 'before_script');
            $yamlAsArray['before_script'][] = 'if [[ $DUPLICATE_CODE_CHECK ]]; then sudo apt remove -y nodejs && curl '
                . '-sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && sudo bash nodesource_setup.sh '
                . '&& sudo apt install -y build-essential nodejs && which npm && npm install jscpd@3.2.1  ;fi';

            // run jscpd on src and tests dir
            $helper->ensurePathExistsInYaml($yamlAsArray, 'script');
            $yamlAsArray['script'][] = 'if [[ $DUPLICATE_CODE_CHECK ]]; then node_modules/jscpd/bin/jscpd src && '
                . 'node_modules/jscpd/bin/jscpd tests ; fi';
        }

        $helper->saveTravis($yamlAsArray);
    }



}
