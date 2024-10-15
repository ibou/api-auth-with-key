<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ApiKey;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiKeyUser implements UserInterface
{

    public function __construct(private ApiKey $apiKey)
    {
    }


    public function getRoles(): array
    {
        return $this->apiKey->getScopes();
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->apiKey->getId();
    }

    public function getApiKey(): ApiKey
    {
        return $this->apiKey;
    }

    public function setApiKey(ApiKey $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }


}