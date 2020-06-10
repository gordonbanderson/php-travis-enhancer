# PHP Travis Enhancer
[![Build Status](https://travis-ci.org/gordonbanderson/php-travis-enhancer.svg?branch=master)](https://travis-ci.org/gordonbanderson/php-travis-enhancer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gordonbanderson/php-travis-enhancer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gordonbanderson/php-travis-enhancer/?branch=master)
[![codecov.io](https://codecov.io/github/gordonbanderson/php-travis-enhancer/coverage.svg?branch=master)](https://codecov.io/github/gordonbanderson/php-travis-enhancer?branch=master)


[![Latest Stable Version](https://poser.pugx.org/suilven/php-travis-enhancer/version)](https://packagist.org/packages/suilven/php-travis-enhancer)
[![Latest Unstable Version](https://poser.pugx.org/suilven/php-travis-enhancer/v/unstable)](//packagist.org/packages/suilven/php-travis-enhancer)
[![Total Downloads](https://poser.pugx.org/suilven/php-travis-enhancer/downloads)](https://packagist.org/packages/suilven/php-travis-enhancer)
[![License](https://poser.pugx.org/suilven/php-travis-enhancer/license)](https://packagist.org/packages/suilven/php-travis-enhancer)
[![Monthly Downloads](https://poser.pugx.org/suilven/php-travis-enhancer/d/monthly)](https://packagist.org/packages/suilven/php-travis-enhancer)
[![Daily Downloads](https://poser.pugx.org/suilven/php-travis-enhancer/d/daily)](https://packagist.org/packages/suilven/php-travis-enhancer)
[![composer.lock](https://poser.pugx.org/suilven/php-travis-enhancer/composerlock)](https://packagist.org/packages/suilven/php-travis-enhancer)

[![GitHub Code Size](https://img.shields.io/github/languages/code-size/gordonbanderson/php-travis-enhancer)](https://github.com/gordonbanderson/php-travis-enhancer)
[![GitHub Repo Size](https://img.shields.io/github/repo-size/gordonbanderson/php-travis-enhancer)](https://github.com/gordonbanderson/php-travis-enhancer)
[![GitHub Last Commit](https://img.shields.io/github/last-commit/gordonbanderson/php-travis-enhancer)](https://github.com/gordonbanderson/php-travis-enhancer)
[![GitHub Activity](https://img.shields.io/github/commit-activity/m/gordonbanderson/php-travis-enhancer)](https://github.com/gordonbanderson/php-travis-enhancer)
[![GitHub Issues](https://img.shields.io/github/issues/gordonbanderson/php-travis-enhancer)](https://github.com/gordonbanderson/php-travis-enhancer/issues)

![codecov.io](https://codecov.io/github/gordonbanderson/php-travis-enhancer/branch.svg?branch=master)

Add stricter PHPStan and PHPCS coding standard checks, linting, Psalm standards checking, and detection of code
duplication to your PHP projects.

---
# Usage
## Introduction
The goal of this package is to add strict coding checks to your PHP packages.  I'd added these tasks manuallly to a
couple of modules manually, and decided it was time to automate this as far as possible.  Hence the existence of this
package.

Any or all of the following coding checks can be added:

* lint - simply parses your PHP code looking for syntax errors, the build will break if there are some
* phpcs - checks PHP code against a subsection of the Slevomat coding standards.  Some of the checks oppose each other,
I have commented those out in `ruleset.xml` that break code fixing.  Automated fixes can then be achieved using
`composer fixcs`, but some kinds of fixes can only be done manually.
* phpstan - run PHPStan, a static PHP analysis tool, over your codebase.  I have set the level to a fairly strict value
of 6, you may wish to manually change this
* psalm - run Psalm, an alternative static analysis checker.  Note this is somewhat strict regarding PHPDoc, which hey
is a good thing.
* duplication - this is provided as an extra check for Travis, the build will fail if swathes of duplicate code is found.

## Warning!!
Note that this module alters your codebase, as such run this on a backed up source tree,
preerably in a separate branch from master so that a pull request can later be submitted.

*The following files are altered*

* `travis.yml` - additional code quality checks added to the matrix, install and running steps
* `composer.json` - packages required for code checking, scripts to run code checks
* `ruleset.xml` - Based on PSR2 but with the addition of the Slevomat coding standards
* `tests/phpstan.neon` - basic config file for phpstan to autoload files

## Adding Code Checking Tools
### Installation
```bash
composer require --dev suilven/php-travis-enhancer
```
### Adding All Checks
This will take several minutes to run.  If identical flags already exist, ditto coomposer script names, then these are
left as is in `.travis.yml` and `composer.json` respecctively.

```bash
vendor/bin/phpte all
```

## Adding Individual Checks
Checks can be added indiviually if so desired.
```bash
vendor/bin/phpte phpstan
vendor/bin/phpte phpcs
vendor/bin/phpte lint
vendor/bin/phpte duplication
vendor/bin/phpte psalm
```

Note that the script `checkCode` in the altered composer file will only include the individual command installed.

### Removal
This module is one shot, as such remove it after being used
```bash
composer remove suilven/php-travis-enhancer
```

### Checking Code Locally
If the checks were installed using `all` then `composer checkCode` will run through the code checks.  It is an alias for
```bash
composer checkcs && composer lint && composer psalm && composer phpstan
```
If any one of these fail it will exit immediately.

### What Does a Run Look Like?
This is snipped but hopefully enough to provide context.  Here I am testing on an old small SilverStripe module.
```
> composer checkcs
> vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests
EE 2 / 2 (100%)



FILE: /var/www/src/PrevNextSiblingExtension.php
----------------------------------------------------------------------
FOUND 9 ERRORS AFFECTING 6 LINES
----------------------------------------------------------------------
  1 | ERROR | [x] Missing declare(strict_types = 1).
    |       |     (SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing)
 10 | ERROR | [ ] Method name "PrevNextSiblingExtension::NextSibling"
    |       |     is not in camel caps format
    |       |     (PSR1.Methods.CamelCapsMethodName.NotCamelCaps)
 10 | ERROR | [x] Expected 2 blank lines after method, found 1.
    |       |     (SlevomatCodingStandard.Classes.MethodSpacing.IncorrectLinesCountBetweenMethods)
 10 | ERROR | [ ] Method
    |       |     \WebOfTalent\PrevNextSibling\PrevNextSiblingExtension::NextSibling()
    |       |     does not have return type hint nor @return
    |       |     annotation for its return value.
    |       |     (SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint)
 13 | ERROR | [x] Useless variable $result.
    |       |     (SlevomatCodingStandard.Variables.UselessVariable.UselessVariable)
 14 | ERROR | [x] Expected 1 lines before "return", found 0.
    |       |     (SlevomatCodingStandard.ControlStructures.JumpStatementsSpacing.IncorrectLinesCountBeforeControlStructure)
 17 | ERROR | [ ] Method name
    |       |     "PrevNextSiblingExtension::PreviousSibling" is not
    |       |     in camel caps format
    |       |     (PSR1.Methods.CamelCapsMethodName.NotCamelCaps)
 17 | ERROR | [ ] Method
    |       |     \WebOfTalent\PrevNextSibling\PrevNextSiblingExtension::PreviousSibling()
    |       |     does not have return type hint nor @return
    |       |     annotation for its return value.
    |       |     (SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint)
 20 | ERROR | [x] Expected 1 lines before "return", found 0.
    |       |     (SlevomatCodingStandard.ControlStructures.JumpStatementsSpacing.IncorrectLinesCountBeforeControlStructure)
----------------------------------------------------------------------
PHPCBF CAN FIX THE 5 MARKED SNIFF VIOLATIONS AUTOMATICALLY
----------------------------------------------------------------------

```

### Automatically Fixing Errors
Those errors marked above with an `X` can be automatically fixed.

```
> composer fixcs
> vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests
FF 2 / 2 (100%)



PHPCBF RESULT SUMMARY
----------------------------------------------------------------------
FILE                                                  FIXED  REMAINING
----------------------------------------------------------------------
/var/www/src/PrevNextSiblingExtension.php             5      4
/var/www/tests/PrevNextSiblingExtensionTest.php       15     0
----------------------------------------------------------------------
A TOTAL OF 20 ERRORS WERE FIXED IN 2 FILES
----------------------------------------------------------------------


```


### Run Code Check After Automatic Fixing
These errors need fixed manually
```
> composer checkcs
> vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests
E. 2 / 2 (100%)



FILE: /var/www/src/PrevNextSiblingExtension.php
----------------------------------------------------------------------
FOUND 4 ERRORS AFFECTING 2 LINES
----------------------------------------------------------------------
 10 | ERROR | Method name "PrevNextSiblingExtension::NextSibling" is
    |       | not in camel caps format
    |       | (PSR1.Methods.CamelCapsMethodName.NotCamelCaps)
 10 | ERROR | Method
    |       | \WebOfTalent\PrevNextSibling\PrevNextSiblingExtension::NextSibling()
    |       | does not have return type hint nor @return annotation
    |       | for its return value.
    |       | (SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint)
 18 | ERROR | Method name "PrevNextSiblingExtension::PreviousSibling"
    |       | is not in camel caps format
    |       | (PSR1.Methods.CamelCapsMethodName.NotCamelCaps)
 18 | ERROR | Method
    |       | \WebOfTalent\PrevNextSibling\PrevNextSiblingExtension::PreviousSibling()
    |       | does not have return type hint nor @return annotation
    |       | for its return value.
    |       | (SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint)
----------------------------------------------------------------------

```

## Afterthoughts
I've had a go at applying this to a reasonably large codebase (Manticore PHP Search Client) and gave up due to the sheer
volume of errors.  As such I am uing this with new projects (including this one), and retrofixing old small projects.

---

## License

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

- **[MIT license](http://opensource.org/licenses/mit-license.php)**
- Copyright 2020 © <a href="http://gordonbanderson.com" target="_blank">Gordon Anderson</a>.
