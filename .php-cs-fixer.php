<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.13.2|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PhpCsFixer' => true,
        // 'strict_param' => true,
        '@PSR12' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        // ->exclude('folder-to-exclude') // if you want to exclude some folders, you can do it like this!
        ->in(__DIR__)
    )
;
