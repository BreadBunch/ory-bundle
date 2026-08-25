<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bread\Ory\Bundle\Factory\OryClientFactory;
use Bread\Ory\Contracts\Client\OryClientFactoryInterface;
use Ory\Client\Api\FrontendApi;
use Ory\Client\Api\IdentityApi;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $vendor = 'ory';

    $services->set($vendor . '.client.factory', OryClientFactory::class)
        ->arg('$baseUrl', param($vendor . '.client.base_url'));

    $services->alias(OryClientFactoryInterface::class, $vendor . '.client.factory');

    $services->set($vendor.'.frontend.api', FrontendApi::class)
        ->factory([service(OryClientFactoryInterface::class), 'createFrontendApi']);

    $services->set($vendor.'.identity.api', IdentityApi::class)
        ->factory([service(OryClientFactoryInterface::class), 'createIdentityApi']);
};
