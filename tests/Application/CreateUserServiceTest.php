<?php

declare(strict_types=1);

namespace SimpleBank\Tests\Application;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Application\Service\User\CreateUserService;
use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\InvalidUserException;
use SimpleBank\Domain\Model\User\NegativeBalanceException;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;
use SimpleBank\Domain\Transactions;
use SimpleBank\Infrastructure\Persistence\User\InMemoryUserBalanceRepository;
use SimpleBank\Infrastructure\Persistence\User\InMemoryUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateUserServiceTest extends KernelTestCase
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

    public function testCreateUserSaveShouldReturnTrue()
    {
        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->commit()
            ->shouldBeCalledOnce()
            ->willReturn(true);

        $createUserService = new CreateUserService(
            $this->userRepository,
            $this->userBalanceRepository,
            $this->transactionManager->reveal()
        );

        $bankBranchDto = new BankBranchDto();
        $bankBranchDto->setName('Hugo Money Sl');
        $bankBranchDto->setLocation('Zaragoza, Calle Caballo 7');

        $bankBranch = new BankBranch(
            new BankBranchId(),
            $bankBranchDto->name(),
            $bankBranchDto->location()
        );

        $userDto = new UserDto();
        $userDto->setName('pippo');
        $userDto->setBankBranchId($bankBranch->id()->id());
        $userDto->setBalance(76060);

        $this->assertTrue($createUserService->save($userDto));
    }

    public function testCreateUserSaveThrowsExceptionWhenBalanceIsNegative()
    {
        $this->expectException(NegativeBalanceException::class);

        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->rollBack()
            ->shouldBeCalledOnce();

        $createUserService = new CreateUserService(
            $this->userRepository,
            $this->userBalanceRepository,
            $this->transactionManager->reveal()
        );

        $bankBranchDto = new BankBranchDto();
        $bankBranchDto->setName('Hugo Money Sl');
        $bankBranchDto->setLocation('Zaragoza, Calle Caballo 7');

        $bankBranch = new BankBranch(
            new BankBranchId(),
            $bankBranchDto->name(),
            $bankBranchDto->location()
        );

        $userDto = new UserDto();
        $userDto->setName('pippo');
        $userDto->setBankBranchId($bankBranch->id()->id());
        $userDto->setBalance(-76060);

        $createUserService->save($userDto);
    }

    public function testCreateUserSaveThrowsExceptionWhenNameIsMissing()
    {
        $this->expectException(InvalidUserException::class);

        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->rollBack()
            ->shouldBeCalledOnce();

        $createUserService = new CreateUserService(
            $this->userRepository,
            $this->userBalanceRepository,
            $this->transactionManager->reveal()
        );

        $bankBranchDto = new BankBranchDto();
        $bankBranchDto->setName('Hugo Money Sl');
        $bankBranchDto->setLocation('Zaragoza, Calle Caballo 7');

        $bankBranch = new BankBranch(
            new BankBranchId(),
            $bankBranchDto->name(),
            $bankBranchDto->location()
        );

        $userDto = new UserDto();
        $userDto->setName('');
        $userDto->setBankBranchId($bankBranch->id()->id());
        $userDto->setBalance(76060);

        $createUserService->save($userDto);
    }
}
