<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

/**
 * @var \TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Source\Caller1 $caller1
 */

$c = new MyClass();
$c->myMethod([$caller1, 'callIt']);

class MyClass {
    function myMethod(callable $c) {}
}
