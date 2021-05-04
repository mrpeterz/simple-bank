<?php

declare(strict_types=1);

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankTransferDto;
use SimpleBank\Application\Service\BankBranch\Exception\InvalidArgumentBankTransferException;
use SimpleBank\Application\Service\BankBranch\Exception\NegativeAmountException;
use SimpleBank\Application\Service\BankBranch\Exception\InsufficientBalanceException;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\NegativeBalanceException;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;

class BankBranchTransferService
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

    public function wireTransfer(BankTransferDto $bankTransferDto): bool
    {
        $this->transactionalManager->beginTransaction();

        try{

            if (!$bankTransferDto->toUserId() ||
                !$bankTransferDto->fromUserId() ||
                !$bankTransferDto->amount()
            ) {
                throw new InvalidArgumentBankTransferException('Bank transfer parameters cannot be null.');
            }


            if ($bankTransferDto->amount() <= 0) {
                throw new NegativeAmountException('Amount must be greater than 0.');
            }

            $userFrom = $this->userFromRepository($bankTransferDto->fromUserId());

            if ($userFrom->userBalance()->balance() < $bankTransferDto->amount()) {
                throw new InsufficientBalanceException('User balance is less than amount.');
            }

            $userTo = $this->userFromRepository($bankTransferDto->toUserId());

            $this->doWireTransfer($userFrom, $userTo, $bankTransferDto->amount());

            return $this->transactionalManager->commit();

        }catch (\Exception $exception) {
            $this->transactionalManager->rollBack();
            throw $exception;
        }
    }

    private function doWireTransfer(
        User $userFrom,
        User $userTo,
        float $amount
    ): void {
        $userFrom->setBalance($userFrom->userBalance()->balance() - $amount);
        $this->userBalanceRepository->updateBalance($userFrom);

        $userTo->setBalance($userTo->userBalance()->balance() + $amount);
        $this->userBalanceRepository->updateBalance($userTo);
    }

    private function userFromRepository(string $userId): User
    {
        $user = $this->userRepository->search(new UserId($userId));

        if (!$user) {
            throw new NegativeBalanceException('User not exists.');
        }

        return User::fromArray($user);
    }
}
