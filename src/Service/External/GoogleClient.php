<?php

declare(strict_types=1);

namespace App\Service\External;

use App\Model\External\GoogleUserDTO;
use Google\Client;
use Google\Service\Oauth2;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class GoogleClient
{
    public function __construct(private string $credentialsFile, private Client $client, private UrlGeneratorInterface $urlGenerator)
    {
        $this->client->setAuthConfig($this->credentialsFile);
        $this->client->setApplicationName('SmartFreelancer');
        $this->setRedirectUri($this->urlGenerator->generate('app_google_connect_check', [], UrlGeneratorInterface::ABSOLUTE_URL));
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
