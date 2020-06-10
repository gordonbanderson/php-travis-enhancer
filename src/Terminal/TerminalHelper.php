<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Terminal;

use League\CLImate\CLImate;
use splitbrain\phpcli\Options;
use Suilven\PHPTravisEnhancer\Task\AddDuplicationCheckTask;
use Suilven\PHPTravisEnhancer\Task\AddPHPLintTask;

trait TerminalHelper
{
    /**
     * Render a green tick in the terminal
     *
     * @psalm-suppress UndefinedMagicMethod
     */
    private function tick()
    {
        $this->climate->bold()->dark()->green('✓');
    }


    /**
     * Render a red cross in the terminial
     */
    private function cross()
    {
        $this->climate->bold()->red('✘');
    }


    /**
     * @param string $message
     * @param int $retVal
     */
    private function taskReport($message, $retVal = 0)
    {
        $this->climate->inline($message . '  ');
        if ($retVal !== 0) {
            $this->cross();
        } else {
            $this->tick();
        }
    }
}
