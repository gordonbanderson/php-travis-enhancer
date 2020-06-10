<?php declare(strict_types = 1);

namespace Suilven\PHPTravisEnhancer\Task;

use Suilven\PHPTravisEnhancer\Abstraction\TaskBase;
use Suilven\PHPTravisEnhancer\IFace\Task;

class AddPHPCSTask extends TaskBase implements Task
{
    /** @return string bash variable for use in Travis script */
    public function getFlag(): string
    {
        return 'PHPCS_TEST';
    }


    public function getCommand(): string
    {
        return 'phpcs';
    }


    public function getTravisBeforeScript(): ?string
    {
        return null;
    }


    public function getTravisScript(): ?string
    {
        return 'vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests';
    }


    /** @return array<string,string> */
    public function getComposerScripts(): array
    {
        return [
            "checkcs"=> "vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests",
            "fixcs" => "vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests",
        ];
    }


    /** @return array<string> */
    public function getComposerPackages(): array
    {
        return ['slevomat/coding-standard'];
    }


    /** @return array<string, string> */
    public function filesToCopy(): array
    {
        return ['files/ruleset.xml' => 'ruleset.xml'];
    }


    public function isCodeCheck(): bool
    {
        return true;
    }
}
