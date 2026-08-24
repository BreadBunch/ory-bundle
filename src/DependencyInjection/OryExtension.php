<?php

declare(strict_types=1);

namespace Bread\Ory\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Configures the Bread Ory bundle services and loads extension configuration.
 *
 * This extension integrates Ory services into the Symfony container,
 * imports configuration files, and provides a unique alias for the bundle.
 */
final class OryExtension extends AbstractExtension
{
    /**
     * Configures the extension's definitions by importing external configuration files.
     *
     * @param DefinitionConfigurator $definition The definition configurator used to import
     *                                           service definitions and parameters.
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../../config/definition.php');
    }

    /**
     * Loads the extension's configuration and services into the container.
     *
     * This method imports the main service configuration file for the Ory bundle.
     *
     * @param array                 $config    The processed configuration values for this extension.
     * @param ContainerConfigurator $container The container configurator used to import resources.
     * @param ContainerBuilder      $builder   The container builder instance (unused but required by signature).
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../../config/ory.php');
    }

    /**
     * Prepends configuration to other bundles before they are loaded.
     *
     * This method calls the parent implementation to allow any prepend logic
     * defined in the parent class. Currently, no additional prepend operations
     * are performed.
     *
     * @param ContainerConfigurator $container The container configurator for prepending configuration.
     * @param ContainerBuilder      $builder   The container builder instance.
     */
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        parent::prependExtension($container, $builder);
    }

    /**
     * Returns the alias for this extension.
     *
     * The alias is used to reference the extension's configuration in the
     * application's configuration files (e.g., config/packages/bread_ory.yaml).
     *
     * @return string The extension alias string.
     */
    public function getAlias(): string
    {
        return 'bread_ory';
    }
}
