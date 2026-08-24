<?php

declare(strict_types=1);

namespace Bread\Ory\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class OryExtension extends AbstractExtension
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../../config/definition.php');
    }
}
