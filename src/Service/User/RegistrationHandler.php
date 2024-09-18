<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Exception\InvalidPayloadException;
use App\Exception\ResourceAlreadyExistsException;
use App\Model\User\CreateUserPayload;
use App\Model\User\JWTTokenDTO;
use App\Model\User\RegistrationPayload;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationHandler
{
    public function __construct(
        private readonly Creator $userCreator,
        private readonly UserRepository $userRepository,
        private readonly ValidatorInterface $validator,
        private readonly JWTTokenGetter $tokenGetter,
    ) {
    }

    public function __invoke(RegistrationPayload $payload): JWTTokenDTO
    {
        if ($payload->getPassword() !== $payload->getConfirmPassword()) {
            throw new InvalidPayloadException('Passwords does not match');
        }

        if ($this->userRepository->findOneByEmail($payload->getEmail())) {
            throw new ResourceAlreadyExistsException('user');
        }

        if ($this->validator->validate($payload->getEmail(), [new Email()])->count() > 0) {
            throw new InvalidPayloadException('Invalid email');
        }

        $user = ($this->userCreator)(CreateUserPayload::from([
            'email' => $payload->getEmail(),
            'password' => $payload->getPassword(),
        ]));

        return ($this->tokenGetter)($user);
    }
}
