<?php

namespace Illegal\InsideAuth\Tests\FeatureHelpers\Contracts;

use Illegal\InsideAuth\Authenticator;
use Illegal\InsideAuth\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Pest\Support\HigherOrderTapProxy;
use function Pest\Laravel\actingAs;

abstract class Helper
{
    public function __construct(protected readonly Authenticator $auth, protected readonly ?User $user = null)
    {
    }

    /**
     * Return the base test case, authenticated if we have a user
     * @noinspection PhpInternalEntityUsedInspection
     */
    protected function testCase(string $guard = 'test'): TestCase|\PHPUnit\Framework\TestCase|HigherOrderTapProxy
    {
        return null === $this->user ? test() : actingAs($this->user, $guard);
    }
}