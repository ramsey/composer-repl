<h1 align="center">ramsey/composer-repl</h1>

<p align="center">
    <strong>A REPL for PHP built into Composer.</strong>
</p>

<p align="center">
    <a href="https://github.com/ramsey/composer-repl"><img src="http://img.shields.io/badge/source-ramsey/composer--repl-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/ramsey/composer-repl"><img src="https://img.shields.io/packagist/v/ramsey/composer-repl.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/ramsey/composer-repl.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <br>
    <a href="https://github.com/ramsey/composer-repl/actions?query=workflow%3Amain"><img src="https://img.shields.io/github/workflow/status/ramsey/composer-repl/main?logo=github&style=flat-square" alt="Build Status"></a>
    <a href="https://codeclimate.com/github/ramsey/composer-repl"><img src="https://img.shields.io/codeclimate/coverage/ramsey/composer-repl?logo=Code%20Climate&style=flat-square" alt="Code Coverage"></a>
    <a href="https://shepherd.dev/github/ramsey/composer-repl"><img src="https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fshepherd.dev%2Fgithub%2Framsey%2Fcomposer-repl%2Fcoverage" alt="Psalm Type Coverage"></a>
    <a href="https://codeclimate.com/github/ramsey/composer-repl"><img src="https://img.shields.io/codeclimate/coverage-letter/ramsey/composer-repl?label=maintainability&logo=Code%20Climate&style=flat-square" alt="Maintainability"></a>
    <br>
    <a href="https://github.com/ramsey/composer-repl/blob/master/LICENSE"><img src="https://img.shields.io/packagist/l/ramsey/composer-repl.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://packagist.org/packages/ramsey/composer-repl/stats"><img src="https://img.shields.io/packagist/dt/ramsey/composer-repl.svg?style=flat-square&colorB=darkmagenta" alt="Package downloads on Packagist"></a>
    <a href="https://phpc.chat/channel/ramsey"><img src="https://img.shields.io/badge/phpc.chat-%23ramsey-darkslateblue?style=flat-square" alt="Chat with the maintainers"></a>
</p>

## About

This [Composer](https://getcomposer.org) plugin provides the `composer repl`
command.

REPL stands for *read-eval-print loop*. It's a language shell that reads user
input, evaluates the input using a programming language (in this case, PHP), and
prints the output to the screen. Then, it returns to the read state (that's the
*loop* part).

[PsySH](https://psysh.org) is the REPL providing the magic behind
ramsey/composer-repl. PsySH is a language shell for PHP. It's similar to
[irb](https://github.com/ruby/irb) for Ruby, [IPython](https://ipython.org) for
Python, and [JShell](https://docs.oracle.com/javase/9/tools/jshell.htm) for
Java. In addition to acting as a language shell, PsySH can also function as an
interactive debugger and development console.
[Laravel Tinker](https://github.com/laravel/tinker), [Drush](https://www.drush.org)
for Drupal, [WP-CLI shell](https://github.com/wp-cli/shell-command)
for WordPress, [CakePHP console](https://book.cakephp.org/3/en/console-and-shells/repl.html),
and [Yii shell](https://github.com/yiisoft/yii2-shell) are a few of the projects
using PsySH.

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## Installation

Install this package as a development dependency using
[Composer](https://getcomposer.org).

``` bash
composer require --dev ramsey/composer-repl
```

## Usage

Open your terminal and type `composer repl`. You may also type `composer shell`,
if you prefer.

You'll see something similar to this:

```
Psy Shell v0.10.4 (PHP 7.4.9 — cli) by Justin Hileman
------------------------------------------------------------------------
Welcome to the development console (REPL) for ramsey/conventional-commits.
To learn more about what you can do in PsySH, type `help`.
------------------------------------------------------------------------
>>>
```

While in the dev console, you can do cool things like this:

``` php
>>> $hello = 'Hello, world'
=> "Hello, world"

>>> echo $hello
Hello, world

>>> foreach ([1, 2, 3] as $x) echo $x . "\n"
1
2
3

>>> $date = new DateTimeImmutable();
=> DateTimeImmutable @1598393282 {#6953
     date: 2020-08-25 22:08:02.643076 UTC (+00:00),
   }

>>> $getDate = fn (DateTimeInterface $dt): DateTimeInterface => $dt;
=> Closure(DateTimeInterface $dt): DateTimeInterface {#6964 …3}

>>> t assertInstanceOf(DateTimeInterface::class, $date);
Test passed!

>>> t assertSame($date, $getDate($date))
Test passed!

>>> phpunit

PHPUnit 9.3.7 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.4.9
Configuration: /path/to/ramsey/conventional-commits/phpunit.xml.dist

...............................................................  63 / 221 ( 28%)
............................................................... 126 / 221 ( 57%)
............................................................... 189 / 221 ( 85%)
................................                                221 / 221 (100%)

Time: 00:00.064, Memory: 12.00 MB

OK (221 tests, 484 assertions)
```

This implementation of PsySH has Super ElePHPant Powers.

## Contributing

Contributions are welcome! Before contributing to this project, familiarize
yourself with [CONTRIBUTING.md](CONTRIBUTING.md).

To develop this project, you will need [PHP](https://www.php.net) 7.4 or greater
and [Composer](https://getcomposer.org).

After cloning this repository locally, execute the following commands:

``` bash
cd /path/to/repository
composer install
```

Now, you are ready to develop!

## Copyright and License

The ramsey/composer-repl library is copyright © [Ben Ramsey](https://benramsey.com)
and licensed for use under the terms of the
MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
