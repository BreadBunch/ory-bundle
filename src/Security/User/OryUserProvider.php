<?php

namespace Bread\Ory\Bundle\Security\User;

use Bread\Ory\Contracts\Security\User\OryUserProviderInterface;
use Ory\Client\Api\IdentityApi;
use Ory\Client\ApiException;
use Ory\Client\Model\Identity;
use Override;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class OryUserProvider implements OryUserProviderInterface
{
    public function __construct(
        private IdentityApi $identityApi,
        private array $defaultRoles = ['ROLE_USER']
    )
    {}

    #[Override]
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            $identity = $this->identityApi->getIdentity($identifier);
            
            return $this->convertIdentity($identity);
        } catch (ApiException $e) {
            $exception = new UserNotFoundException(
                sprintf('User with identifier "%s" not found in Ory.', $identifier), 
                0, 
                $e
            );
            $exception->setUserIdentifier($identifier);
            
            throw $exception;
        }
    }

    #[Override]
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    #[Override]
    public function supportsClass(string $class): bool
    {
        return is_a($class, UserInterface::class, true);
    }

    public function convertIdentity(Identity $identity): OryUser
    {
        if ($identity->getState() !== 'active') {
            throw new CustomUserMessageAuthenticationException('User account is disabled or inactive.');
        }

        $traits = $identity->getTraits();
        $traitsArray = is_object($traits) || is_array($traits) ? (array) $traits : [];

        return new OryUser(
            oryId: $identity->getId(),
            userIdentifier: $this->extractIdentifier($traitsArray, $identity->getId()),
            roles: $this->defaultRoles,
            traits: $traitsArray
        );
    }

    private function extractIdentifier(array $traits, string $fallbackId): string
    {
        if (isset($traits['email']) && is_string($traits['email']) && $traits['email'] !== '') {
            return $traits['email'];
        }
        
        if (isset($traits['username']) && is_string($traits['username']) && $traits['username'] !== '') {
            return $traits['username'];
        }
        
        return $fallbackId;
    }
}
