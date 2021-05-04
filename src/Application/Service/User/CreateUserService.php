<?php

declare(strict_types=1);

namespace SimpleBank\Application\Service\User;

use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\InvalidUserException;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\NegativeBalanceException;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;

class CreateUserService
{
    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalanceRepository;
    private Transactions $transactionalManager;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserBalanceRepositoryInterface $userBalanceRepository,
        Transactions $transactionalManager
    ) {
        $this->userRepository = $userRepository;
        $this->userBalanceRepository = $userBalanceRepository;
        $this->transactionalManager = $transactionalManager;
    }

    public function save(UserDto $userDto): bool
    {
        $this->transactionalManager->beginTransaction();

        try {
            if ($userDto->balance() < 0) {
                throw new NegativeBalanceException('Balance must be positive.');
            }

            if (!$userDto->name()) {
                throw new InvalidUserException('Invalid info');
            }

            $user = new User(
                $this->userRepository->nextIdentity(),
                $userDto->name(),
                new BankBranchId($userDto->bankBranchId()),
                $userDto->balance()
            );

            $this->userRepository->save($user);
            $this->userBalanceRepository->save($user->userBalance());

            return $this->transactionalManager->commit();

        }catch(\Exception $exception) {
            $this->transactionalManager->rollBack();
            throw $exception;
        }
    }
}
