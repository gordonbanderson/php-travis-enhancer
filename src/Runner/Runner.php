<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Runner;

use League\CLImate\CLImate;
use splitbrain\phpcli\Options;
use Suilven\PHPTravisEnhancer\Task\AddDuplicationCheckTask;
use Suilven\PHPTravisEnhancer\Task\AddPHPLintTask;

class Runner
{
    /** @var \League\CLImate\CLImate */
    private $climate;

    public function __construct()
    {
        $this->climate = new CLImate();
        $this->climate->clear();
        $this->climate->out('RUNNER');
        $this->climate->error('Ruh roh.');
        $this->climate->border();
        $this->climate->comment('Just so you know.');
        $this->climate->whisper('Not so important, just a heads up.');
        $this->climate->shout('This. This is important.');
        $this->climate->info('Nothing fancy here. Just some info.');

        $progress = $this->climate->progress()->total(100);

        for ($i = 0; $i <= 100; $i++) {
            $progress->current($i);

            // Simulate something happening
            \usleep(800);
        }

        $this->climate->inline('Doing some task ');
        $this->tick();

        $this->climate->inline('Doing another task ');
        $this->cross();
    }


    public function run(Options $options): void
    {
        $this->climate->bold('PHP Travis Enhancer - Audit Your Code To The Max');

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
                $this->climate->red('No known command was called, we show the default help instead:');
                echo $options->help();
                exit;
        }
    }


    private function addCodingStandardsCheck(): void
    {
        $this->climate->black('Trying to add coding standards check ');
        $task = new \Suilven\PHPTravisEnhancer\Task\AddPHPCSTask();
        $task->run();
    }


    private function addDuplicationCheck(): void
    {
        $this->climate->black('Trying to add duplication checker ');
        $task = new AddDuplicationCheckTask();
        $task->run();
    }


    private function addPhpLint(): void
    {
        $this->climate->black('Trying to add linting ');
        $task = new AddPHPLintTask();
        $task->run();
    }


    private function addPhpStan(): void
    {
        $this->climate->black('Trying to add PHPStan ');
        $task = new \Suilven\PHPTravisEnhancer\Task\AddPHPStanTask();
        $task->run();
    }


    private function addPsalm(): void
    {
        $this->climate->black('Trying to add Psalm ');
        $task = new \Suilven\PHPTravisEnhancer\Task\AddPsalmTask();
        $task->run();
    }


    private function tick(): void
    {
        $this->climate->bold()->darkGreen('✓');
    }


    private function cross(): void
    {
        $this->climate->bold()->red('✘');
    }
}
