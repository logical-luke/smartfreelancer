<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(string $email, string $password): User
    {
        if (null !== $this->userRepository->findOneBy(['email' => $email])) {
            throw new \RuntimeException('Email already exists');
        }

        $user = (new User())
            ->setEmail($email);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->userRepository->persist($user);
        $this->userRepository->flush();

        return $user;
    }
}
