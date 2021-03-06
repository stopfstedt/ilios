<?php

declare(strict_types=1);

namespace App\Controller;

use App\Classes\SessionUserInterface;
use App\Repository\AuthenticationRepository;
use App\Repository\UserRepository;
use App\Service\AuthenticationInterface;
use App\Service\JsonWebTokenManager;
use App\Entity\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthController extends AbstractController
{

    /**
     * Authenticate someone
     * Passes off the task of authentication to the service selected by the config
     * option authentication_type.
     *
     * @param Request $request
     * @param AuthenticationInterface $authenticator
     *
     * @return JsonResponse
     */
    public function loginAction(Request $request, AuthenticationInterface $authenticator)
    {
        return $authenticator->login($request);
    }

    /**
     * Get the id fro the currently authenticated user
     *
     * @param TokenStorageInterface $tokenStorage
     * @return JsonResponse
     */
    public function whoamiAction(TokenStorageInterface $tokenStorage)
    {
        $token = $tokenStorage->getToken();
        if ($token->isAuthenticated()) {
            /** @var SessionUserInterface $sessionUser */
            $sessionUser = $token->getUser();
            if ($sessionUser instanceof SessionUserInterface) {
                return new JsonResponse(['userId' => $sessionUser->getId()], JsonResponse::HTTP_OK);
            }
        }

        return new JsonResponse(['userId' => null], JsonResponse::HTTP_OK);
    }

    /**
     * Get a new token
     * Useful when the time limit is approaching but the user is still active
     *
     * @param Request $request
     * @param TokenStorageInterface $tokenStorage
     * @param JsonWebTokenManager $jwtManager
     *
     * @return JsonResponse
     */
    public function tokenAction(Request $request, TokenStorageInterface $tokenStorage, JsonWebTokenManager $jwtManager)
    {
        $token = $tokenStorage->getToken();
        if ($token->isAuthenticated()) {
            $sessionUser = $token->getUser();
            if ($sessionUser instanceof SessionUserInterface) {
                $ttl = $request->get('ttl') ? $request->get('ttl') : 'PT8H';
                $jwt = $jwtManager->createJwtFromSessionUser($sessionUser, $ttl);
                return new JsonResponse(['jwt' => $jwt], JsonResponse::HTTP_OK);
            }
        }

        return new JsonResponse(['jwt' => null], JsonResponse::HTTP_OK);
    }

    /**
     * Logout
     * Passes off the task of logout to the service selected by the config
     * option authentication_type.
     *
     * @param Request $request
     * @param AuthenticationInterface $authenticator
     *
     * @return JsonResponse
     */
    public function logoutAction(Request $request, AuthenticationInterface $authenticator)
    {
        return $authenticator->logout($request);
    }

    /**
     * Invalidate all tokens issued before now
     * Resets authentication in case a token is compromised
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function invalidateTokensAction(
        TokenStorageInterface $tokenStorage,
        UserRepository $userRepository,
        AuthenticationRepository $authenticationRepository,
        JsonWebTokenManager $jwtManager
    ) {
        $now = new \DateTime();
        $token = $tokenStorage->getToken();
        if ($token->isAuthenticated()) {
            /** @var SessionUserInterface $sessionUser */
            $sessionUser = $token->getUser();
            if ($sessionUser instanceof SessionUserInterface) {
                /** @var UserInterface $user */
                $user = $userRepository->findOneBy(['id' => $sessionUser->getId()]);
                $authentication = $authenticationRepository->findOneBy(['user' => $user->getId()]);
                if (!$authentication) {
                    $authentication = $authenticationRepository->create();
                    $authentication->setUser($user);
                }

                $authentication->setInvalidateTokenIssuedBefore($now);
                $authenticationRepository->update($authentication);

                sleep(1);
                $jwt = $jwtManager->createJwtFromSessionUser($sessionUser);

                return new JsonResponse(['jwt' => $jwt], JsonResponse::HTTP_OK);
            }
        }

        throw new \Exception('Attempted to invalidate token with no valid user');
    }
}
