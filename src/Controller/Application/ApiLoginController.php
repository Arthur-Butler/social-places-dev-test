<?php

namespace App\Controller\Application;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods:'POST')]
    public function login(#[CurrentUser] ?User $user): Response {
        if ($user === null) {
            return $this->json(['error' => 'Unable to authenticate user'], Response::HTTP_UNAUTHORIZED);
        }
        $bytes = random_bytes(10);
        $token = bin2hex($bytes);

        return $this->json([
            'message' => 'Welcome to socialplace!',
-           'path' => 'src/Controller/ApiLoginController.php',
+           'user'  => $user->getUserIdentifier(),
+           'token' => $token,
        ]);
    }

    #[Route('/api/logout', name: 'api_logout', methods: 'POST')]
    public function logout(Security $security): Response {
        $response = $security->logout();
        return $this->redirect($this->generateUrl('login'));
    }
}
