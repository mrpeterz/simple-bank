<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankTransferDto;
use SimpleBank\Application\Service\BankBranch\Exception\AmountNotPositiveException;
use SimpleBank\Application\Service\BankBranch\Exception\BalanceNotSufficientException;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserNotExistsException;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;

class BankBranchTransferService
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
                throw new AmountNotPositiveException('Amount must be greater than 0.');
            }

            $userFrom = $this->userFromRepository($bankTransferDto->fromUserId());

            if ($userFrom->userBalance()->balance() < $bankTransferDto->amount()) {
                throw new BalanceNotSufficientException('User balance is less than amount.');
            }

            $userTo = $this->userFromRepository($bankTransferDto->toUserId());

            $this->doWireTransfer($userFrom, $userTo, $bankTransferDto->amount());

            $this->transactionalManager->commit();

            return true;

        }catch (\Exception $exception) {
            $this->transactionalManager->rollBack();
            return false;
        }
    }

    private function doWireTransfer(
        User $userFrom,
        User $userTo,
        float $amount
    ): void {
        $this->userBalancesRepository->updateBalance(
            $userFrom->updateBalance($userFrom->userBalance()->balance() - $amount)
        );

        $this->userBalancesRepository->updateBalance(
            $userTo->updateBalance($userTo->userBalance()->balance() + $amount)
        );
    }

    private function userFromRepository(string $userId): User
    {
        $user = $this->userRepository->search(new UserId($userId));

        if (!$user) {
            throw new UserNotExistsException('User not exists.');
        }

        return User::fromArray($user);
    }
}
