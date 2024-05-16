<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordHasher\PasswordHasherInterface;

class Sha256PasswordHasher implements \Symfony\Component\PasswordHasher\PasswordHasherInterface
{
    public function hash(string $plainPassword, UserInterface $user = null): string
    {
        return hash('sha256', $plainPassword);
    }

    public function verify(string $hashedPassword, string $submittedPassword, UserInterface $user = null): bool
    {
        return hash_equals($hashedPassword, hash('sha256', $submittedPassword));
    }

    public function needsRehash(string $hashedPassword, UserInterface $user = null): bool
    {
        // You can implement a rehashing strategy here if needed
        return false;
    }
}