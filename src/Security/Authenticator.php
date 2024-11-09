<?php

namespace App\Security;

use App\Repository\ParticipantRepository; // Make sure to import your repository
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class Authenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private ParticipantRepository $userRepository;

    public function __construct(UrlGeneratorInterface $urlGenerator, ParticipantRepository $userRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository; // Inject the repository
    }

    public function authenticate(Request $request): Passport
    {
        $usernameOrEmail = $request->get('username_or_email');
        $password = $request->get('_password');

        return new Passport(
            new UserBadge($usernameOrEmail, function ($identifier) {
                return $this->userRepository->findOneBy(['email' => $identifier])
                    ?? $this->userRepository->findOneBy(['pseudo' => $identifier]);
            }),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('authenticate', $request->get('_csrf_token'))]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // RBC
        if (in_array('ROLE_ADMIN', $token->getUser()->getRoles())) {

            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }

        // Redirect regular user to the home page
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
