<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Helper;

use Symfony\Component\Yaml\Yaml;

class TravisYMLHelper
{

    /** @var string */
    private $travisFileName = '.travis.yml';

    /**
     * TravisYMLHelper constructor.
     *
     * @param string $travisFileName The name of the travis config file, by default .travis.yml, but injectable for
     * testing purposes
     */
    public function __construct(string $travisFileName = '.travis.yml')
    {
        $this->travisFileName = $travisFileName;
    }


    /**
     * Load the Travis file, or default to an empty array if it does not exist
     *
     * @todo Change this behavior?
     * @return mixed The Travis YAML file as an array (note Symfony returns mixed)
     */
    public function loadTravis()
    {
        $result = [];
        $path = \getcwd() . '/' . $this->travisFileName;
        if (\file_exists($this->travisFileName)) {
            $result = Yaml::parseFile($path);
        }

        return $result;
    }


    /**
     * Save a travis file, default .travis.yml, in the root of a project
     *
     * @todo How does one specifiy this as an associative array?
     * @param array<string> $yamlArray an array that ought to have been formed from a YAML file
     */
    public function saveTravis(array $yamlArray): void
    {
        $yaml = Yaml::dump($yamlArray, Yaml::DUMP_OBJECT_AS_MAP);
        \file_put_contents($this->travisFileName, $yaml);
    }
}
