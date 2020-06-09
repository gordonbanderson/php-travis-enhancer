<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Runner;

use League\CLImate\CLImate;
use Symfony\Component\Yaml\Yaml;

class Runner
{
    public function run() {
        $climate = new CLImate();
        $climate->bold('PHP Travis Enhancer - Audit Your Code To The Max');
    }
}
