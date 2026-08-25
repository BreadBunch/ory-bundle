<?php

namespace Bread\Ory\Bundle\Security\User;

use Bread\Ory\Contracts\Security\User\OryUserInterface;

class OryUser implements OryUserInterface
{
    /**
     * @param string[] $roles
     * @param array<string, mixed> $traits
     */
    public function __construct(
        private string $oryId,
        private string $userIdentifier,
        private array $roles = ['ROLE_USER'],
        private array $traits = []
    ) 
    {}

    public function getOryId(): string
    {
        return $this->oryId;
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getTraits(): array
    {
        return $this->traits;
    }

    public function eraseCredentials(): void
    {}
}
