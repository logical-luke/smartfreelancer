<?php

declare(strict_types=1);

namespace App\Service\External;

use App\Model\External\GoogleUserDTO;
use Google\Client;
use Google\Service\Oauth2;

readonly class GoogleClient
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private string $feAppBaseUrl,
        private Client $client,
    ) {
        $this->client->setClientId($this->clientId);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setApplicationName('SmartFreelancer');
        $this->setRedirectUri(sprintf('%s/google/login', $this->feAppBaseUrl));
    }

    public function setRedirectUri(string $redirectUri): self
    {
        $this->client->setRedirectUri($redirectUri);

        return $this;
    }

    public function setScopes(array $scopes): self
    {
        $this->client->setScopes($scopes);

        return $this;
    }

    public function setState(string $state): self
    {
        $this->client->setState($state);

        return $this;
    }

    public function createAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function authorize(string $code): GoogleUserDTO
    {
        $tokens = $this->client->fetchAccessTokenWithAuthCode($code);

        $oauth = new Oauth2($this->client);
        $email = $oauth->userinfo->get()->getEmail();

        return new GoogleUserDTO($email, $tokens['access_token'], $tokens['id_token']);
    }
}
