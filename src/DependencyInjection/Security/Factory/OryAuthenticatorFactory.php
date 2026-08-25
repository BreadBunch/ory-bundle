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
                ->scalarNode('provider')
                    ->defaultNull()
                ->end()
                ->scalarNode('authenticator')
                    ->defaultValue('ory.security.client.api_authenticator')
                ->end()
                ->scalarNode('session_cookie_name')
                    ->info('Overriding the Ory session cookie name for a specific firewall')
                    ->defaultNull()
                ->end()
                ->scalarNode('success_handler')
                    ->defaultNull()
                    ->info('Service ID for handling successful authentication')
                ->end()
                ->scalarNode('failure_handler')
                    ->defaultNull()
                    ->info('ID of the service for handling authentication errors')
                ->end()
            ->end();
    }

    #[Override]
    public function createAuthenticator(ContainerBuilder $container, string $firewallName, array $config, string $userProviderId): string|array
    {
        $successHandlerId = $config['success_handler'] ?? $this->createSuccessHandler($container, $firewallName, $config);
        $failureHandlerId = $config['failure_handler'] ?? $this->createFailureHandler($container, $firewallName, $config);
        $authenticatorId = 'security.authenticator.ory.' . $firewallName;

        $userProviderId = empty($config['provider']) ? $userProviderId : 'security.user.provider.concrete.' . $config['provider'];

        $container
            ->setDefinition($authenticatorId, new ChildDefinition($config['authenticator']))
            ->replaceArgument(2, new Reference($successHandlerId))
            ->replaceArgument(3, new Reference($failureHandlerId));
            // ->replaceArgument(4, $config['session_cookie_name'] ?? '%ory.security.session_cookie_name%');

        return $authenticatorId;
    }

    private function createSuccessHandler(ContainerBuilder $container, string $firewallName, array $config): string
    {
        $handlerId = 'security.authentication.success_handler.' . $firewallName . '.ory';
        $container
            ->setDefinition($handlerId, new ChildDefinition('ory.security.success_handler.abstract'));

        return $handlerId;
    }

    private function createFailureHandler(ContainerBuilder $container, string $firewallName, array $config): string
    {
        $handlerId = 'security.authentication.failure_handler.' . $firewallName . '.ory';
        $container
            ->setDefinition($handlerId, new ChildDefinition('ory.security.failure_handler.abstract'));

        return $handlerId;
    }
}
