<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bread\Ory\Bundle\Factory\OryClientFactory;
use Bread\Ory\Contracts\Client\OryClientFactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Ory\Client\Api\FrontendApi;
use Ory\Client\Api\IdentityApi;
use Symfony\Component\HttpClient\GuzzleHttpHandler;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $vendor = 'ory';

    $services->set('guzzle.handler', GuzzleHttpHandler::class);

    $services->set('guzzle.client', Client::class)
        ->arg('$config', [
            'handler' => service('guzzle.handler'),
        ]);
    $services->alias(ClientInterface::class, 'guzzle.client');

    $services->set($vendor . '.client.factory', OryClientFactory::class)
        ->arg('$baseUrl', param($vendor . '.client.base_url'))
        ->arg('$httpClient', service('guzzle.client'));

    $services->alias(OryClientFactoryInterface::class, $vendor . '.client.factory');

    $services->set($vendor.'.frontend.api', FrontendApi::class)
        ->factory([service(OryClientFactoryInterface::class), 'createFrontendApi']);

    $services->set($vendor.'.identity.api', IdentityApi::class)
        ->factory([service(OryClientFactoryInterface::class), 'createIdentityApi']);
};
