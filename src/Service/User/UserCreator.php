<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Model\User\CreateUserPayload;
use App\Model\User\LoginTypeEnum;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Autoconfigure(lazy: true)]
class UserCreator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(CreateUserPayload $payload): User
    {
        $email = $payload->getEmail();

        if (null !== $this->userRepository->findOneBy(['email' => $email])) {
            throw new \RuntimeException('Email already exists');
        }

        $user = (new User())
            ->setEmail($email);

        if (LoginTypeEnum::EMAIL === $payload->getLoginType()) {
            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $payload->getPassword());
            $user->setPassword($hashedPassword);
        }

        $this->userRepository->save($user, true);

        return $user;
    }
}
