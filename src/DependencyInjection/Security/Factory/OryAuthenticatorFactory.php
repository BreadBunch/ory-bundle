<?php

namespace Bread\Ory\Bundle\DependencyInjection\Security\Factory;

use Override;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AuthenticatorFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OryAuthenticatorFactory implements AuthenticatorFactoryInterface
{
    public function __construct()
    {}

    #[Override]
    public function getKey(): string
    {
        return 'ory';
    }

    #[Override]
    public function getPriority(): int
    {
        return -10;
    }

    #[Override]
    public function addConfiguration(NodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode('session_cookie_name')
                    ->info('Overriding the Ory session cookie name for a specific firewall')
                    ->defaultNull()
                ->end()
            ->end();
    }

    #[Override]
    public function createAuthenticator(ContainerBuilder $container, string $firewallName, array $config, string $userProviderId): string|array
    {
        $authenticatorId = 'security.authenticator.ory.' . $firewallName;

        return $authenticatorId;
    }
}
