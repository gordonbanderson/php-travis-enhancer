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

    /** @var array<string> The names of the commands, e.g. phpstan, phpcs */
    private static $codeCheckCommands = [];

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
     * @return int 0 if task successful, error code if not
     */
    public function run(string $travisFile = '.travis.yml'): int
    {
        $this->climate->border();
        $this->climate->info('Adding ' . $this->getCommand());
        $this->climate->border();

        // install composer packages
        $retVal = $this->installPackages();
        if ($retVal !== 0) {
            $this->climate->error('Packages could not be installed, not altering Travis or adding config files');

            return $retVal;
        }

        $this->addScriptsToComposerJSON();


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

            $beforeScript = $this->getTravisBeforeScript();
            if (isset($beforeScript)) {
                // install jdscpd, node tool, for duplication detection
                $helper->ensurePathExistsInYaml($yamlAsArray, 'before_script');
                $yamlAsArray['before_script'][] = $prefix . $beforeScript . '  ;fi';
                $this->taskReport('Added before script: ' . $beforeScript);
            }

            $script = $this->getTravisScript();
            if (isset($script)) {
                // run jscpd on src and tests dir
                $helper->ensurePathExistsInYaml($yamlAsArray, 'script');
                $yamlAsArray['script'][] = $prefix . $script . ' ; fi';
                $this->taskReport('Added script: ' . $script);
            }
        }

        $helper->saveTravis($yamlAsArray);

        $this->copyFiles();

        $this->climate->br(4);

        // signal success
        return 1;
    }


    /**
     * Copy and files required for this task to their appropriate place in the hierarchy of the main project
     */
    private function copyFiles(): void
    {
        $fileTransferArray = $this->filesToCopy();
        foreach ($fileTransferArray as $srcFile => $destFile) {
            $destFile = \str_replace('SRC_DIR', 'src', $destFile);
            $destFile = \str_replace('TESTS_DIR', 'tests', $destFile);

            $absoluteSrcFile = __DIR__ . '/../../' . $srcFile;
            $absoluteDestFile = \getcwd() . '/' . $destFile;

            error_log('SRC FILE: ' . $absoluteSrcFile);
            error_log('DEST FILE: ' . $absoluteDestFile);

            // @todo Replace YOUR_PROJECT with composer project name
            $contents = \file_get_contents($absoluteSrcFile);
            \file_put_contents( $absoluteDestFile, $contents);

            $this->taskReport('Copied ' . $absoluteSrcFile . ' to ' . $absoluteDestFile);
        }
    }


    /**
     * Install the composer packages for this task
     *
     * @return int 0 for no error, otherwise an error code
     */
    private function installPackages(): int
    {
        $retVal = 0;

        $packages = $this->getComposerPackages();
        if (\sizeof($packages) > 0) {
            foreach ($packages as $package) {
                $cmd = 'composer --verbose --profile require --dev ' . $package;
                $output = [];
                \exec($cmd, $output, $retVal);
                $this->taskReport('Installing ' . $package, $retVal);
            }
        }

        return $retVal;
    }


    private function addScriptsToComposerJSON(): void
    {
        $composerFile = \getcwd() . '/composer.json';
        $contents = \file_get_contents($composerFile);
        $json = \json_decode($contents, true);
        if (!isset($json['scripts'])) {
            $json['scripts'] = [];
        }
        $scriptsInJSON = $json['scripts'];
        $existingScriptNames = \array_keys($scriptsInJSON);
        \error_log(\print_r($existingScriptNames, true));

        $this->climate->info('COmposer file: ' . $composerFile);
        /** @var array<string,string> $scripts */
        $scripts = $this->getComposerScripts();

        $this->climate->border();
        \error_log(\print_r($scriptsInJSON, true));
        $this->climate->border();

        foreach ($scripts as $scriptName => $bashCode) {
            $this->climate->out('Adding script ' . $scriptName . ' --> ' . $bashCode);
            if (!\in_array($scriptName, $existingScriptNames, true)) {
                $scriptsInJSON[$scriptName] = $bashCode;
                ;
            } else {
                $this->climate->error('Script ' . $scriptName . ' already exists');
            }
            if (!$this->isCodeCheck() || $scriptName === 'fixcs') {
                continue;
            }

            self::$codeCheckCommands[] = 'composer ' . $scriptName;
        }

        $scriptsInJSON['checkCode'] = \implode(' && ', self::$codeCheckCommands);

        $json['scripts'] = $scriptsInJSON;
        $contents = \json_encode($json, \JSON_PRETTY_PRINT);
        \file_put_contents($composerFile, $contents);
    }
}
