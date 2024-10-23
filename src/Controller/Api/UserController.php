<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends  AbstractController
{
    #[Route('/app/me', name: 'users.index')]
    #[IsGranted('ROLE_USER')]
    public function me(): JsonResponse
    {

        return $this->json($this->getUser(), 200, [], [
            'groups' => 'users:read'
        ]);
    }

}