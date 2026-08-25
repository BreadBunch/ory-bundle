<?php

declare(strict_types=1);

namespace Bread\Ory\Bundle;

use Bread\Ory\Bundle\DependencyInjection\OryExtension;
use Bread\Ory\Bundle\DependencyInjection\Security\Factory\OryAuthenticatorFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class OryBundle extends AbstractBundle
{

    /**
     * Returns the bundle extension responsible for handling configuration.
     *
     * This method is called by the container to load and process configuration from `config/packages/services.php`
     * 
     * @return ExtensionInterface|null
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        // Return an instance of the bundle's DI extension
        return new OryExtension();
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        /** @var SecurityExtension $extension */
        $extension = $container->getExtension('security');

        $extension->addAuthenticatorFactory(new OryAuthenticatorFactory());
    }
}
