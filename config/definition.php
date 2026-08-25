<?php

declare(strict_types=1);

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition): void 
{
    $rootNode = $definition->rootNode();

    $rootNode->children()

        ->arrayNode('client')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('base_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Base URL of the Ory instance (e.g., https://project.projects.oryapis.com)')
                ->end()
                ->integerNode('timeout')
                    ->defaultValue(5)
                    ->info('HTTP request timeout for the Ory API in seconds')
                ->end()
            ->end()
        ->end()
        
    ->end();
};
