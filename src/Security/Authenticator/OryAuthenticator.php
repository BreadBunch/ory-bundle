<?php

namespace Bread\Ory\Bundle\Security\Authenticator;

use Bread\Ory\Bundle\Client\Exception\ApiException;
use Bread\Ory\Contracts\Security\User\OryUserProviderInterface;
use Ory\Client\Api\FrontendApi;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class OryAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly FrontendApi $frontendApi,
        private readonly ?OryUserProviderInterface $userProvider = null,
        private readonly ?AuthenticationSuccessHandlerInterface $successHandler = null,
        private readonly ?AuthenticationFailureHandlerInterface $failureHandler = null,
        private string $sessionCookieName = 'ory_kratos_session'
    )
    {}

    #[Override]
    public function supports(Request $request): ?bool
    {
        return $request->cookies->has($this->sessionCookieName) 
            || $this->extractBearerToken($request) !== null;
    }

    #[Override]
    public function authenticate(Request $request): Passport
    {
        $cookieHeader = $this->extractCookieHeader($request);
        $bearerToken = $this->extractBearerToken($request);
        
        $session = $this->frontendApi->toSession(
            xSessionToken: $bearerToken,
            cookie: $cookieHeader
        );

        $identity = $session->getIdentity();

        return new SelfValidatingPassport(
            new UserBadge(
                $identity->getId(), 
                fn() => $this->userProvider->convertIdentity($identity)
            )
        );
    }

    #[Override]
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->failureHandler->onAuthenticationFailure($request, $exception);
    }

    #[Override]
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    private function extractBearerToken(Request $request): ?string
    {
        $authHeader = $request->headers->get('Authorization');
        
        if ($authHeader !== null && str_starts_with($authHeader, 'Bearer ')) {
            return substr($authHeader, 7);
        }

        return null;
    }

    private function extractCookieHeader(Request $request): ?string
    {
        $cookie = $request->cookies->get($this->sessionCookieName);
        
        if ($cookie !== null) {
            return sprintf('%s=%s', $this->sessionCookieName, $cookie);
        }
        
        return null;
    }
}
