<?php

namespace BB\Ory\Bundle\Factory;

use BB\Ory\Contracts\Client\OryClientFactoryInterface;
use GuzzleHttp\ClientInterface;
use Ory\Client\Api\FrontendApi;
use Ory\Client\Api\IdentityApi;
use Ory\Client\Configuration;
use Override;

class OryClientFactory implements OryClientFactoryInterface
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $baseUrl,
    )
    {}

    #[Override]
    public function getConfiguration(): Configuration
    {
        return Configuration::getDefaultConfiguration()
            ->setHost($this->baseUrl);
    }

    #[Override]
    public function createFrontendApi(): FrontendApi
    {
        return new FrontendApi(
            $this->httpClient,
            $this->getConfiguration()
        );
    }

    #[Override]
    public function createIdentityApi(): IdentityApi
    {
        return new IdentityApi(
            $this->httpClient,
            $this->getConfiguration()
        );
    }
}
