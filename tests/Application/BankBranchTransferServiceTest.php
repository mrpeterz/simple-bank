<?php

namespace SimpleBank\Tests\Application;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Application\DataTransformer\BankBranch\BankTransferDto;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Application\Service\BankBranch\BankBranchTransferService;
use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalance;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;
use SimpleBank\Infrastructure\Persistence\BankBranch\InMemoryBankBranchRepository;
use SimpleBank\Infrastructure\Persistence\User\InMemoryUserBalanceRepository;
use SimpleBank\Infrastructure\Persistence\User\InMemoryUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BankBranchTransferServiceTest extends KernelTestCase
{
    use ProphecyTrait;

    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalanceRepository;
    private BankBranchRepositoryInterface $bankBranchRepository;
    private $transactionManager;

    public function setUp(): void
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->userBalanceRepository = new InMemoryUserBalanceRepository();
        $this->bankBranchRepository = new InMemoryBankBranchRepository();
        $this->transactionManager = $this->prophesize(Transactions::class);
    }

    public function testBankBranchTransferServiceDoWireTransaction()
    {
        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->commit()
            ->shouldBeCalledOnce()
            ->willReturn(true);

        $bankBranchTransferService = new BankBranchTransferService(
            $this->userRepository,
            $this->userBalanceRepository,
            $this->transactionManager->reveal()
        );

        $bankBranchDto = new BankBranchDto();
        $bankBranchDto->setName('hola');
        $bankBranchDto->setLocation('zaragoza');

        $bankBranch =
            new BankBranch(
                new BankBranchId(),
                $bankBranchDto->name(),
                $bankBranchDto->location()
            );

        $this->bankBranchRepository->save($bankBranch);

        $userFromDto = new UserDto();
        $userFromDto->setName('pippo');
        $userFromDto->setBalance(700);

        $userFrom = new User(
            new UserId(),
            $userFromDto->name(),
            $bankBranch->id(),
            $userFromDto->balance(),
        );

        $userToDto = new UserDto();
        $userToDto->setName('pazzo');
        $userToDto->setBalance(500);

        $userTo = new User(
            new UserId(),
            $userToDto->name(),
            $bankBranch->id(),
            $userToDto->balance(),
        );

        $this->userRepository->save($userFrom);
        $this->userBalanceRepository->save($userFrom->userBalance());

        $this->userRepository->save($userTo);
        $this->userBalanceRepository->save($userTo->userBalance());

        $bankTransferDto = new BankTransferDto();
        $bankTransferDto->setFromUserId($userFrom->id());
        $bankTransferDto->setToUserId($userTo->id());
        $bankTransferDto->setAmount(700);

        $this->assertTrue($bankBranchTransferService->wireTransfer($bankTransferDto));
        $this->assertEquals(0, $userFrom->userBalance()->balance());
        $this->assertEquals(1200, $userTo->userBalance()->balance());
    }
}
