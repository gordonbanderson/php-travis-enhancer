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

        $this->ensurePathExistsInYaml($yamlAsArray, 'matrix/include');

        $foundExisting = false;
        foreach ($yamlAsArray['matrix']['include'] as $entry) {
            $env = $entry['env'];
            if ($env !== 'DUPLICATE_CODE_CHECK=1') {
                continue;
            }

            $foundExisting = true;
        }

        if ($foundExisting) {
            return;
        } else {
            // add a matrix entry
            $yamlAsArray['matrix']['include'][] = [
                'php' => 7.4,
                'env' => 'DUPLICATE_CODE_CHECK=1',
            ];

            // install jdscpd, node tool, for duplication detection
            $this->ensurePathExistsInYaml($yamlAsArray, 'before_script');
            $yamlAsArray['before_script'][] = 'if [[ $DUPLICATE_CODE_CHECK ]]; then sudo apt remove -y nodejs && curl '
                . '-sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && sudo bash nodesource_setup.sh '
                . '&& sudo apt install -y build-essential nodejs && which npm && npm install jscpd@3.2.1  ;fi';

            // run jscpd on src and tests dir
            $this->ensurePathExistsInYaml($yamlAsArray, 'script');
            $yamlAsArray['script'][] = 'if [[ $DUPLICATE_CODE_CHECK ]]; then node_modules/jscpd/bin/jscpd src && '
                . 'node_modules/jscpd/bin/jscpd tests ; fi';
        }

        $helper->saveTravis($yamlAsArray);
    }


    /**
     * Ensure that a hierarchy exists in a PHP array. Pass the hierarchy in the form matrix/include, this will populate
     * the path an empty leaf array
     *
     * @param array $yamlAsArray YAML file converted into an array. Can of course be any array
     * @param string $path The hierarchy required to exist, in the form matrix/include (forward slash separated)
     */
    private function ensurePathExistsInYaml(array &$yamlAsArray, string $path): void
    {
        $pathParts = \explode('/', $path);
            $part = \array_shift($pathParts);
        if (!isset($yamlAsArray[$part])) {
            $yamlAsArray[$part] = [];
        }
            $remainingPath = \implode('/', $pathParts);
        if (\sizeof($pathParts) === 0) {
            return;
        }

        $this->ensurePathExistsInYaml($yamlAsArray[$part], $remainingPath);
    }
}
