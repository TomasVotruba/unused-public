<?php

declare(strict_types=1);

namespace TomasVotruba\UnusedPublic\Tests\Rules\UnusedPublicPropertyRule\Fixture;

abstract class AbstractBaseclass {
	/**
	 * @var Provider Export provider object.
	 */
	protected $provider;

	/**
	 * @param Provider $provider Export Provider
	 */
	 public function __construct($provider) {
		$this->provider = $provider;
	}
}
