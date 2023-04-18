<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

class MyBaseClass {
    public function usedMethod() {}

    /**
     * @return self|false
     */
    static public function getIt()
    {
    }
}

class MySubClass extends MyBaseClass {

}

function doFoo() {
    $obj = MySubClass::getIt();
    $obj->usedMethod();
}
