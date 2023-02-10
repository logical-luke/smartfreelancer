<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\ConfirmationPasswordIsNotSame;
use App\Exception\InvalidPayloadException;
use App\Exception\UserAlreadyExistsException;
use App\Model\User\RegistrationPayload;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationHandler
{
    public function __construct(
        private readonly UserCreator $userCreator,
        private readonly UserRepository $userRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(RegistrationPayload $payload): User
    {
        if ($payload->getPassword() !== $payload->getConfirmPassword()) {
            throw new InvalidPayloadException('Confirm password does not match');
        }

        if ($this->userRepository->findOneByEmail($payload->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        if ($this->validator->validate($payload->getEmail(), [new Email()])->count() > 0) {
            throw new InvalidPayloadException('Invalid email');
        }

        return ($this->userCreator)($payload->getEmail(), $payload->getPassword());
    }
}
