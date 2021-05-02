<?php

namespace SimpleBank\Application\Service\User;

use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserNotExistsException;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;

class CreateUserService
{
    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalancesRepository;
    private Transactions $transactionalManager;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserBalanceRepositoryInterface $userBalanceRepository,
        Transactions $transactionalManager
    ) {
        $this->userRepository = $userRepository;
        $this->userBalancesRepository = $userBalanceRepository;
        $this->transactionalManager = $transactionalManager;
    }

    public function save(UserDto $userDto): bool
    {
        $this->transactionalManager->beginTransaction();

        try {
            $user = new User(
                $this->userRepository->nextIdentity(),
                $userDto->name(),
                new BankBranchId($userDto->bankBranchId()),
                $userDto->balance()
            );

            if (!$user) {
                throw new UserNotExistsException('User cannot be null.');
            }

            $this->userRepository->save($user);
            $this->userBalancesRepository->save($user->userBalance());

            return $this->transactionalManager->commit();

        }catch(\Exception $exception) {
            $this->transactionalManager->rollBack();
            throw $exception;
        }
    }
}
