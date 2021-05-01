<?php

namespace SimpleBank\Application\Service\User;

use PHPUnit\Util\Exception;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class CreateUser
{
    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalancesRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserBalanceRepositoryInterface $userBalanceRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userBalancesRepository = $userBalanceRepository;
    }

    public function save(UserDto $userDto): bool
    {
        $user = null;

        try{

        $user = new User(
            $this->userRepository->nextIdentity(),
            $userDto->name(),
            new BankBranchId($userDto->bankBranchId()),
            $userDto->balance()
        );

        $this->userRepository->save($user);

        }catch (\Exception $exception) {
            //
        }

        return $this->userBalancesRepository->save($user->balance());
    }
}
