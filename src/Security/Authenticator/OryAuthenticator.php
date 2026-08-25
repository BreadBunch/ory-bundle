<?php

namespace Bread\Ory\Bundle\Security\Authenticator;

use Symfony\Component\HttpFoundation\Request;
use Override;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class OryAuthenticator extends AbstractAuthenticator
{
    public function __construct()
    {}

    #[Override]
    public function supports(Request $request): ?bool
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function authenticate(Request $request): Passport
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        throw new \Exception('Not implemented');
    }
}
