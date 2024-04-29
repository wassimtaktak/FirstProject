<?php

namespace App\Security;

use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginauthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $urlGenerator;
    private $recaptcha;

    public function __construct(UrlGeneratorInterface $urlGenerator, ReCaptcha $recaptcha)
    {
        $this->urlGenerator = $urlGenerator;
        $this->recaptcha = $recaptcha;
    }

    public function authenticate(Request $request): Passport
    {
        $recaptchaResponse = $request->request->get('g-recaptcha-response');
        if (!$this->isRecaptchaValid($recaptchaResponse)) {
            throw new CustomUserMessageAuthenticationException('Invalid reCAPTCHA response.');
        }

        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        $user = $token->getUser();
        if ($user != null) {
            if ($user->getIdrole()->getRole() == "Joueur") {
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            } else {
                return new RedirectResponse($this->urlGenerator->generate('app_utilisateur_index'));
            }
        } else {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    private function isRecaptchaValid(string $response): bool
    {
        return $this->recaptcha->verify($response)->isSuccess();
    }
}
