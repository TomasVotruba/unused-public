<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

class ExtendsAbstract extends AbstractBaseclass {
	/**
	 * @param Provider $provider
	 */
	public function __construct($provider) {
		parent::__construct($provider);

        $this->oauth->setToken($this->provider->social_oauth_token, $this->provider->social_oauth_token_secret);
	}
}
