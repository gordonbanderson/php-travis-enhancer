<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Helper;

use Symfony\Component\Yaml\Yaml;

class TravisYMLHelper
{

    /** @var string */
    private $travisFileName = '.travis.yml';

    public function __construct($travisFileName = '.travis.yml')
    {
        $this->travisFileName = $travisFileName;
    }

    public function loadTravis()
    {
        \error_log('++++ LOADING TRAVIS ++++');
        $result = [];
        $path = \getcwd() . '/' . $this->travisFileName;
        \error_log('PATH: ' . $path);
        if (\file_exists($this->travisFileName)) {
            $result = Yaml::parseFile($path);
        }

        return $result;
    }

}