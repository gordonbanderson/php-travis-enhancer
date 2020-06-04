<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\IFace;

interface TaskInterface
{
    public function getFlag(): void;


    public function run(string $travisFile = '.travis.yml'): void;
}
