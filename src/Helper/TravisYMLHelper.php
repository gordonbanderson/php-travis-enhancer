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
     * Ensure that a hierarchy exists in a PHP array. Pass the hierarchy in the form matrix/include, this will populate
     * the path an empty leaf array
     *
     * @param array<string> $yamlAsArray YAML file converted into an array. Can of course be any associative array
     * @param string $path The hierarchy required to exist, in the form matrix/include (forward slash separated)
     */
    public function ensurePathExistsInYaml(array &$yamlAsArray, string $path): void
    {
        $pathParts = \explode('/', $path);
        $part = \array_shift($pathParts);
        if (!isset($yamlAsArray[$part])) {
            $yamlAsArray[$part] = [];
        }
        $remainingPath = \implode('/', $pathParts);
        if (\sizeof($pathParts) === 0) {
            return;
        }

        $this->ensurePathExistsInYaml($yamlAsArray[$part], $remainingPath);
    }


    public function checkForExistingInEnv($yamlAsArray, $flag)
    {
        $foundExisting = false;
        foreach ($yamlAsArray['matrix']['include'] as $entry) {
            $env = $entry['env'];
            if ($env !== ($flag . '=1')) {
                continue;
            }

            $foundExisting = true;
        }

        return $foundExisting;
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
