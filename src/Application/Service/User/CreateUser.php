<?php

namespace SimpleBank\Application\Service\User;

use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;

class CreateUser
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

            if (!$userDto) {
                throw new \Exception('Missing user informations.');
            }

            $user = new User(
                $this->userRepository->nextIdentity(),
                $userDto->name(),
                new BankBranchId($userDto->bankBranchId()),
                $userDto->balance()
            );

            if (!$user) {
                throw new \Exception('User cannot be null.');
            }

            $this->userRepository->save($user);
            $this->userBalancesRepository->save($user->userBalance());

            $this->transactionalManager->commit();
            return true;

        }catch(\Exception $exception) {
            $this->transactionalManager->rollBack();
            return false;
        }
    }
}
