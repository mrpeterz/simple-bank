<?php

namespace SimpleBank\Application\Service\User;

use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class CreateUser
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function save(UserDto $userDto): bool
    {
        //try {
            $user = new User(
                $this->userRepository->nextIdentity(),
                $userDto->getName(),
                new BankBranchId($userDto->getBranchId())
            );

            return $this->userRepository->save($user);
        /*} catch (DbException $exception) {
            //log something

        } catch (BadUuidException $exception) {
            //
        }*/
    }

}
