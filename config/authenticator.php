<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bread\Ory\Bundle\Security\Authenticator\OryAuthenticator;
use Bread\Ory\Bundle\Security\Http\Authentication\OryAuthenticationFailureHandler;
use Bread\Ory\Bundle\Security\Http\Authentication\OryAuthenticationSuccessHandler;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $vendor = 'ory';

    $services->set($vendor.'.security.client.api_authenticator', OryAuthenticator::class)
        ->abstract()
        ->args([
            service($vendor.'.frontend.api'),
            null,
            null,
            null,
        ]);

    $services->set($vendor.'.security.success_handler.abstract', OryAuthenticationSuccessHandler::class)
        ->abstract();

    $services->set($vendor.'.security.failure_handler.abstract', OryAuthenticationFailureHandler::class)
        ->abstract();
};
