<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;

class CreateBankBranch
{
    private BankBranchRepositoryInterface $;

    public function __construct(BankBranchRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function save(BankBranchDto $userDto): bool
    {
        $user = new User(
            $this->userRepository->nextIdentity(),
            $userDto->getName(),
            new BankBranchId($userDto->getBranchId())
        );

        return $this->userRepository->save($user);
    }
}
