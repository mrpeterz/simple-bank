<?php

namespace SimpleBank\Application\Service\User;

use SimpleBank\Domain\Model\User\UserId;
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

    public function searchUsers(string $userId): ?array
    {
        return $this->userRepository->search(new UserId($userId));
    }
}