<?php declare(strict_types=1);

namespace Suilven\PHPTravisEnhancer\Runner;

use League\CLImate\CLImate;
use splitbrain\phpcli\Options;
use Suilven\PHPTravisEnhancer\Task\AddDuplicationCheckTask;
use Suilven\PHPTravisEnhancer\Task\AddPHPLintTask;

class Runner
{
    /**
     * @var Options $options
     */

    public function run($options): void
    {
        $climate = new CLImate();
        $climate->bold('PHP Travis Enhancer - Audit Your Code To The Max');

        var_dump($options);
        $climate->black()->bold('COMMANDS:');
        $climate->green($options->getCmd());


        switch ($options->getCmd()) {
            case 'phpstan':
                $climate->black('Trying to add PHPStan ');
                $task = new \Suilven\PHPTravisEnhancer\Task\AddPHPStanTask();
                $task->run();
                break;
            case 'lint':
                $climate->black('Trying to add linting ');
                $task = new AddPHPLintTask();
                $task->run();
                break;
            case 'cs':
                $climate->black('Trying to add coding standards check ');
                $task = new \Suilven\PHPTravisEnhancer\Task\AddPHPCSTask();
                $task->run();
                break;
            case 'psalm':
                $climate->black('Trying to add Psalm ');
                $task = new \Suilven\PHPTravisEnhancer\Task\AddPsalmTask();
                $task->run();
                break;
            case 'duplication':
                $climate->black('Trying to add duplication checker ');
                $task = new AddDuplicationCheckTask();
                $task->run();
                break;
            default:
                $this->error('No known command was called, we show the default help instead:');
                //echo $options->help();
                exit;
        }
    }
}
