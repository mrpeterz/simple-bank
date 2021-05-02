<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankTransferDto;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;

class BankTransferManager
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

    public function wireTransfer(BankTransferDto $bankTransferDto): bool
    {
        $this->transactionalManager->beginTransaction();

        try{

            if ($bankTransferDto->amount() <= 0) {
                throw new \Exception('Amount must be greater than 0.');
            }

            $userDtoFrom =
                $this
                    ->userRepository
                    ->search(new UserId($bankTransferDto->fromUserId()));

            if (!$userDtoFrom) {
                throw new \Exception('User doesn\'t exist.');
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
                throw new \Exception('User doesn\'t exist.');
            }

            $userTo = User::fromArray($userDtoTo);

            $this
                ->userBalancesRepository
                ->updateBalance(
                    $userFrom->updateBalance(
                        $userFrom->userBalance()->balance() - $bankTransferDto->amount()
                    )
                );

            $this
                ->userBalancesRepository
                ->updateBalance(
                    $userTo->updateBalance(
                        $userTo->userBalance()->balance() + $bankTransferDto->amount()
                    )
                );

            $this->transactionalManager->commit();
            return true;

        }catch (\Exception $exception) {
            $this->transactionalManager->rollBack();
            return false;
        }
    }
}
