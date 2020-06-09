<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Abstraction;

use League\CLImate\CLImate;
use Suilven\PHPTravisEnhancer\Helper\TravisYMLHelper;
use Suilven\PHPTravisEnhancer\IFace\Task;

abstract class TaskBase implements Task
{
    private $climate;

    abstract public function getFlag(): string;


    public function __construct__(): void
    {
        parent::__construct__();

        $this->climate = new CLImate();
    }


    /**
     * Update Travis file to incorporate a check for duplicate code
     *
     * @todo What is the correct annotation to prevent this error?
     * @psalm-suppress PossiblyInvalidArrayOffset
     * @psalm-suppress PossiblyNullOperand - nulls are checked for
     * @param string $travisFile An injectable filename (for testing), leave blank for default of .travis.yml
     */
    public function run(string $travisFile = '.travis.yml'): void
    {
        $helper = new TravisYMLHelper($travisFile);

        /** @var array<string, string|array> $yamlAsArray */
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
                $yamlAsArray['script'][] = $prefix . $script . ' ; fi';
            }
        }

        $helper->saveTravis($yamlAsArray);

        $this->copyFiles();
        $this->installPackages();
    }


    private function copyFiles(): void
    {
        $fileTransferArray = $this->filesToCopy();
        foreach ($fileTransferArray as $srcFile => $destFile) {
            $destFile = \str_replace('SRC_DIR', 'src', $destFile);
            $destFile = \str_replace('TESTS_DIR', 'tests', $destFile);
            \error_log('Will copy ' . $srcFile . ' --> ' . $destFile);

            \error_log(__DIR__);

            // @todo Replace YOUR_PROJECT with composer project name
            $contents = \file_get_contents(__DIR__ . '/../../' . $srcFile);
            \file_put_contents(\getcwd() . '/' . $destFile, $contents);
        }
    }


    private function installPackages(): void
    {
        $packages = $this->getComposerPackages();
        foreach ($packages as $package) {
            $cmd = 'composer --verbose --profile require --dev ' . $package;
            \error_log($cmd);
            $output = [];
            $retVal = -1;
            \exec($cmd, $output, $retVal);
            \error_log('RET VAl: ' . $retVal);
        }
    }
}
