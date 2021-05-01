<?php

namespace SimpleBank\Application\Service\User;

use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class UserFinder
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function listUsers(): ?array
    {
        return $this->userRepository->all();
    }
}
