<?php

namespace App\Security;

use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class Authenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private ParticipantRepository $userRepository;

    public function __construct(UrlGeneratorInterface $urlGenerator, ParticipantRepository $userRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
    }

    public function authenticate(Request $request): Passport
    {
        $usernameOrEmail = $request->get('username_or_email');
        $password = $request->get('_password');

        // Retrieve the participant by email or pseudo
        $participant = $this->userRepository->findOneBy(['email' => $usernameOrEmail])
            ?? $this->userRepository->findOneBy(['pseudo' => $usernameOrEmail]);

        if (!$participant->isActif()) {
            throw new CustomUserMessageAuthenticationException('not active.');
        }

        // Proceed with authentication if the user is active
        return new Passport(
            new UserBadge($usernameOrEmail, fn ($identifier) => $participant),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('authenticate', $request->get('_csrf_token'))]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirect based on the user's role
        if (in_array('ROLE_ADMIN', $token->getUser()->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }

        // Redirect regular users to the home page
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
