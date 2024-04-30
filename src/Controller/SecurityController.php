<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twilio\Rest\Client;

class SecurityController extends AbstractController
{
    private $mailer;
    private $texter;

    public function __construct(MailerInterface $mailer, TexterInterface $texter)
    {
        $this->mailer = $mailer;
        $this->texter = $texter;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_utilisateur_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Handle logout logic here
    }

    #[Route('/password-recovery', name: 'password_recovery')]
    public function passwordRecovery(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $deliveryMethod = $request->request->get('otp_delivery_method');

            $userRepository = $this->getDoctrine()->getRepository(Utilisateur::class);
            $user = $userRepository->findOneBy(['username' => $username]);

            if ($user) {
                $otp = $this->generateOTP();
                $this->sendOTP($user, $otp, $deliveryMethod);
                $this->addFlash('success', sprintf('An OTP has been sent to your %s.', $deliveryMethod));
            } else {
                $this->addFlash('error', 'Username not found. Please try again.');
            }
        }

        return $this->render('security/password_recovery.html.twig');
    }

    private function generateOTP(): string
    {
        return str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function sendOTP(Utilisateur $user, string $otp, string $deliveryMethod): void
    {
        if ($deliveryMethod === 'email') {
            $this->sendOTPByEmail($user, $otp);
        } elseif ($deliveryMethod === 'phone') {
            $this->sendOTPBySMS($user, $otp);
        }
    }

    private function sendOTPByEmail(Utilisateur $user, string $otp): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('forhopeplay@gmail.com', 'Password Recovery'))
            ->to($user->getEmail())
            ->subject('Password Recovery OTP')
            ->htmlTemplate('emails/password_recovery_otp.html.twig')
            ->context([
                'otp' => $otp,
            ]);

        $this->mailer->send($email);
    }

    private function sendOTPBySMS(Utilisateur $user, string $otp): void
    {
        $sid = "ACc97c33ee3e59b4273d6b60b2b95fb0bb";
        $token = "eae890d7d955a456084ed7997973155e";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+21629281941", // to
                array(
                    "from" => "+13184504863",
                    "body" => "this is your otp" . $otp
                )
            );

    }
}
