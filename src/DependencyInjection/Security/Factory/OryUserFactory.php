<?php

namespace Bread\Ory\Bundle\DependencyInjection\Security\Factory;

use Override;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class OryUserFactory implements UserProviderFactoryInterface
{
    #[Override]
    public function create(ContainerBuilder $container, string $id, array $config): void
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function getKey(): string
    {
        return 'ory';
    }

    #[Override]
    public function addConfiguration(NodeDefinition $builder): void
    {
        throw new \Exception('Not implemented');
    }
}
