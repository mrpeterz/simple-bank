<?php

namespace SimpleBank\Infrastructure\Persistence\User;

use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = array();

    public function nextIdentity(): UserId
    {
        return new UserId();
    }

    public function save(User $user): bool
    {
        $this->users[$user->id()->id()] = $user;
        return array_key_exists($user->id()->id(), $this->users);
    }

    public function search(UserId $userId): ?array
    {
        $user = array();

        foreach ($this->users as $u) {
            if ($u->id()->equals($userId)) {
                return $user = [
                    'user_id' => $u->id()->id(),
                    'user_name' => $u->name(),
                    'bank_branch_id' => $u->bankBranchId()->id(),
                    'user_balance' => $u->userBalance()->balance()
                ];
            }
        }

        return $user;
    }

    public function all(): ?array
    {
        return $this->users;
    }

    public function allOthers(UserId $userId): ?array
    {
        $users = array();

        foreach ($this->users as $user) {
            if (!$user->id()->equals($userId)) {
                $users[] = $user;
            }
        }

        return $users;
    }
}
