<?php

namespace Bread\Ory\Bundle\DependencyInjection\Security\Factory;

use Bread\Ory\Bundle\Security\User\OryUser;
use Bread\Ory\Contracts\Security\User\OryUserInterface;
use Override;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class OryUserFactory implements UserProviderFactoryInterface
{
    #[Override]
    public function create(ContainerBuilder $container, string $id, array $config): void
    {
        $container->setDefinition($id, new ChildDefinition('ory.security.provider.abstract'));
            // ->replaceArgument(0, $config['class']);
    }

    #[Override]
    public function getKey(): string
    {
        return 'ory';
    }

    #[Override]
    public function addConfiguration(NodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode('class')
                    ->cannotBeEmpty()
                    ->defaultValue(OryUser::class)
                    ->validate()
                        ->ifTrue(fn ($class) => !is_subclass_of($class, OryUserInterface::class))
                        ->thenInvalid('The %s class must implement ' . OryUserInterface::class . ' for using the "ory" user provider.')
                    ->end()
                ->end()
            ->end()
        ;
    }
}
