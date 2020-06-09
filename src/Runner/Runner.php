<?php declare(strict_types=1);

namespace Suilven\PHPTravisEnhancer\Runner;

use League\CLImate\CLImate;
use splitbrain\phpcli\Options;
use Suilven\PHPTravisEnhancer\Task\AddDuplicationCheckTask;
use Suilven\PHPTravisEnhancer\Task\AddPHPLintTask;

class Runner
{
    /**
     * @var CLImate
     */
    private $climate;

    public function __construct()
    {
        $this->climate = new CLImate();
    }

    /**
     * @var Options $options
     */

    public function run($options): void
    {
        $this->climate->bold('PHP Travis Enhancer - Audit Your Code To The Max');

        var_dump($options);
        $this->climate->black()->bold('COMMANDS:');
        $this->climate->green($options->getCmd());


        switch ($options->getCmd()) {
            case 'phpstan':
                $this->addPhpStan();
                break;
            case 'lint':
                $this->addPhpLint();
                break;
            case 'cs':
                $this->addCodingStandardsCheck();
                $task->run();
                break;
            case 'psalm':
                $this->addPsalm();
                break;
            case 'duplication':
                $this->addDuplicationCheck();
                break;
            case 'all':
                $this->addCodingStandardsCheck();
                $this->addDuplicationCheck();
                $this->addPhpLint();
                $this->addPhpStan();
                $this->addPsalm();
                break;
            default:
                $this->error('No known command was called, we show the default help instead:');
                //echo $options->help();
                exit;
        }
    }


    private function addCodingStandardsCheck()
    {
        $this->climate->black('Trying to add coding standards check ');
        $task = new \Suilven\PHPTravisEnhancer\Task\AddPHPCSTask();
        $task->run();
    }

    private function addDuplicationCheck()
    {
        $this->climate->black('Trying to add duplication checker ');
        $task = new AddDuplicationCheckTask();
        $task->run();
    }

    private function addPhpLint()
    {
        $this->climate->black('Trying to add linting ');
        $task = new AddPHPLintTask();
        $task->run();
    }

    private function addPhpStan()
    {
        $this->climate->black('Trying to add PHPStan ');
        $task = new \Suilven\PHPTravisEnhancer\Task\AddPHPStanTask();
        $task->run();
    }

    private function addPsalm()
    {
        $this->climate->black('Trying to add Psalm ');
        $task = new \Suilven\PHPTravisEnhancer\Task\AddPsalmTask();
        $task->run();
    }
}
