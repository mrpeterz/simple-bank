<?php

namespace SimpleBank\Tests\Application;

use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;
use SimpleBank\Infrastructure\Persistence\User\InMemoryUserBalanceRepository;
use SimpleBank\Infrastructure\Persistence\User\InMemoryUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BankBranchTransferServiceTest extends KernelTestCase
{
    use ProphecyTrait;

    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalanceRepository;
    private $transactionManager;

    public function setUp(): void {
        $this->userRepository = new InMemoryUserRepository();
        $this->userBalanceRepository = new InMemoryUserBalanceRepository();
        $this->transactionManager = $this->prophesize(Transactions::class);
    }

    public function testBankBranchTransferServiceDoWireTransaction()
    {
        
    }
}
