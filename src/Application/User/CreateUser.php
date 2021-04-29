<?php

namespace SimpleBank\Application\User;

use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class CreateUser
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function save(User $user): bool
    {
        $this->userRepository->save($user);
    }
}
