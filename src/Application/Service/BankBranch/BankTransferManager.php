<?php

namespace SimpleBank\Application\Service\BankTransfer;

use Doctrine\DBAL\Driver\Connection;
use SimpleBank\Application\DataTransformer\BankTransfer\BankTransferDto;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class BankTransferManager
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

    public function wireTransfer(BankTransferDto $bankTransferDto): bool
    {
        $this->connection->beginTransaction();

        try{

            $userDtoFrom =
                $this
                    ->userRepository
                    ->search(new UserId($bankTransferDto->fromUserId()));

            if (!$userDtoFrom) {
                throw new \Exception('User not exists.');
            }

            $userFrom = User::fromArray($userDtoFrom);

            if ($userFrom->userBalance()->balance() < $bankTransferDto->amount()) {
                throw new \Exception('User balance is less than amount.');
            }

            $userDtoTo =
                $this
                    ->userRepository
                    ->search(new UserId($bankTransferDto->toUserId()));

            if (!$userDtoTo) {
                throw new \Exception('User not exists.');
            }

            $userTo = User::fromArray($userDtoTo);

            $amountUserFrom = $userFrom->userBalance()->balance() - $bankTransferDto->amount();
            $amountUserTo = $userTo->userBalance()->balance() + $bankTransferDto->amount();

            $this->userBalancesRepository->updateBalance($userFrom->id(), $amountUserFrom);
            $this->userBalancesRepository->updateBalance($userFrom->id(), $amountUserTo);

            $this->connection->commit();
            return true;

        }catch (\Exception $exception) {
            $this->connection->rollBack();
            return false;
        }
    }
}
