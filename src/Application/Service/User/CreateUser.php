<?php

namespace SimpleBank\Application\Service\User;

use Doctrine\DBAL\Driver\Connection;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class CreateUser
{
    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalancesRepository;
    private Connection $connection;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserBalanceRepositoryInterface $userBalanceRepository,
        Connection $connection
    ) {
        $this->userRepository = $userRepository;
        $this->userBalancesRepository = $userBalanceRepository;
        $this->connection = $connection;
    }

    public function save(UserDto $userDto): bool
    {
        $this->connection->beginTransaction();

        try {

            $user = new User(
                $this->userRepository->nextIdentity(),
                $userDto->name(),
                new BankBranchId($userDto->bankBranchId()),
                $userDto->balance()
            );

            $this->userRepository->save($user);
            $this->userBalancesRepository->save($user->userBalance());

            $this->connection->commit();
            return true;

        }catch(\Exception $exception) {
            $this->connection->rollBack();
            return false;
        }
    }
}
