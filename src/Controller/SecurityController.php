<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\ChangePasswordType;
use App\Form\UtilisateurTypeNoRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twilio\Rest\Client;

class SecurityController extends AbstractController
{
    private $mailer;
    private $texter;
    private $session;
    private $em;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, TexterInterface $texter, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->texter = $texter;
        $this->session = $session;
        $this->em = $em;
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
    }

    #[Route(path: '/changePassword', name: 'change_password')]
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->session->get('password_recovery_user');

        $role = $this->em->getRepository(Role::class)->findOneBy(['role' => 'Joueur']);
        $user->setIdRole($role);

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $password = $form->get('password')->getData();

            $hashedPassword = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($hashedPassword);
            //$this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Password changed successfully. Please log in with your new password.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('/security/changepassword.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    #[Route(path: '/code_verification', name: 'code_verification')]
    public function verifyCode(Request $request): Response
    {
        $otp = $request->query->get('otp');
        if ($request->isMethod('POST')) {
            $input = $request->request->get('code');
            $user = $this->session->get('password_recovery_user');
            if ($input == $otp && $user) {
                return $this->redirectToRoute('change_password');
            } else {
                $this->addFlash('error', "wrong otp!");
            }
        }
        return $this->render('/security/verifyotp.html.twig', ['otp' => $otp]);
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
                $this->session->set('password_recovery_user', $user);

                $otp = $this->generateOTP();
                $this->sendOTP($user, $otp, $deliveryMethod);
                $this->addFlash('success', sprintf('An OTP has been sent to your %s.', $deliveryMethod));
                return $this->redirectToRoute('code_verification', ['otp' => $otp]);
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
        $token = "01e642d09f3ae87f14e0555e60ceabf4";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+21629281941", // to
                array(
                    "from" => "+13184504863",
                    "body" => "this is your otp : " . $otp
                )
            );
    }
}
