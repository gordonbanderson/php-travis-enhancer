<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Abstraction;

use Suilven\PHPTravisEnhancer\Helper\TravisYMLHelper;
use Suilven\PHPTravisEnhancer\IFace\Task;

abstract class TaskBase implements Task
{
    abstract public function getFlag(): string;


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

        $foundExisting = $helper->checkForExistingInEnv($yamlAsArray, $this->getFlag());
        if (!$foundExisting) {
            // add a matrix entry
            $yamlAsArray['matrix']['include'][] = [
                'php' => 7.4,
                'env' => $this->getFlag() . '=1',
            ];

            /** @var string $prefix the bash prefix to check for the flag being set */
            $prefix = 'if [[ $' . $this->getFlag() .' ]]; then ';

            $beforeScript = $this->getBeforeScript();
            if (isset($beforeScript)) {
                // install jdscpd, node tool, for duplication detection
                $helper->ensurePathExistsInYaml($yamlAsArray, 'before_script');
                $yamlAsArray['before_script'][] = $prefix . $beforeScript . '  ;fi';
            }

            $script = $this->getScript();
            if (isset($script)) {
                // run jscpd on src and tests dir
                $helper->ensurePathExistsInYaml($yamlAsArray, 'script');
                $yamlAsArray['script'][] = $prefix . $this->getScript() . ' ; fi';
            }
        }

        $helper->saveTravis($yamlAsArray);
    }
}
