<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Abstraction;

use League\CLImate\CLImate;
use Suilven\PHPTravisEnhancer\Helper\TravisYMLHelper;
use Suilven\PHPTravisEnhancer\IFace\Task;
use Suilven\PHPTravisEnhancer\Terminal\TerminalHelper;

abstract class TaskBase implements Task
{
    use TerminalHelper;

    /** @var \League\CLImate\CLImate */
    private $climate;

    abstract public function getFlag(): string;


    /**
     * TaskBase constructor.
     */
    public function __construct()
    {
        $this->climate = new CLImate();
    }


    /**
     * Update Travis file to incorporate for the relevant task
     *
     * @todo What is the correct annotation to prevent this error?
     * @psalm-suppress PossiblyInvalidArrayOffset
     * @psalm-suppress PossiblyNullOperand - nulls are checked for
     * @param string $travisFile An injectable filename (for testing), leave blank for default of .travis.yml
     *
     * @return int 0 if task successful, error code if not
     */
    public function run(string $travisFile = '.travis.yml'): int
    {
        // install composer packages
        $retVal = $this->installPackages();
        if ($retVal) {
            $this->climate->error('Packages could not be installed, not altering Travis or adding config files');
            return $retVal;
        }


        // any alterations post composer failures should be successful
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

            $this->taskReport('Added env option: ' . $this->getFlag() . '=1');

            /** @var string $prefix the bash prefix to check for the flag being set */
            $prefix = 'if [[ $' . $this->getFlag() .' ]]; then ';

            $beforeScript = $this->getBeforeScript();
            if (isset($beforeScript)) {
                // install jdscpd, node tool, for duplication detection
                $helper->ensurePathExistsInYaml($yamlAsArray, 'before_script');
                $yamlAsArray['before_script'][] = $prefix . $beforeScript . '  ;fi';
                $this->taskReport('Added before script: ' . $beforeScript);

            }

            $script = $this->getScript();
            if (isset($script)) {
                // run jscpd on src and tests dir
                $helper->ensurePathExistsInYaml($yamlAsArray, 'script');
                $yamlAsArray['script'][] = $prefix . $script . ' ; fi';
                $this->taskReport('Added script: ' . $script);
            }
        }

        $helper->saveTravis($yamlAsArray);

        $this->copyFiles();

        // signal success
        return 1;
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


    /**
     * Install the composer packages for this task
     *
     * @return int 0 for no error, otherwise an error code
     */
    private function installPackages(): int
    {
        $retVal = 999;

        $packages = $this->getComposerPackages();
        foreach ($packages as $package) {
            $cmd = 'composer --verbose --profile require --dev ' . $package;
            $output = [];
            \exec($cmd, $output, $retVal);
            \error_log('RET VAl: ' . $retVal);
            $this->taskReport('Installing ' . $package, $retVal);
        }

        return $retVal;
    }
}
