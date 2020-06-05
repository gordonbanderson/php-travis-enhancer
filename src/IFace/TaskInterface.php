<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\IFace;

interface TaskInterface
{
    /**
     * @return string
     */
    public function getFlag();

    public function getBeforeScript();


    public function run(string $travisFile = '.travis.yml'): void;
}
