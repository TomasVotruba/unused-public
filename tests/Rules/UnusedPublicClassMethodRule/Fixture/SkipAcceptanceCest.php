<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicClassMethodRule\Fixture;

use Tests\Support\AcceptanceTester;

final class SkipAcceptanceCest
{
    public function showBlogPage(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/blog');

        // see text in blog page
        $acceptanceTester->see('Rector Blog');
    }
}
