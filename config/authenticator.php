<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BB\Ory\Bundle\Security\Authenticator\OryAuthenticator;
use BB\Ory\Bundle\Security\Http\Authentication\OryAuthenticationFailureHandler;
use BB\Ory\Bundle\Security\Http\Authentication\OryAuthenticationSuccessHandler;
use BB\Ory\Bundle\Security\User\OryUserProvider;

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

    $services->set($vendor.'.security.provider.abstract', OryUserProvider::class)
        ->abstract();
};
