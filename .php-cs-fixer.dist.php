<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    '@Symfony' => true,
    'no_unused_imports' => true,
    'full_opening_tag' => false,
])
    ->setFinder($finder);
