<?php

namespace SimpleBank\Tests\Application;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Application\Service\BankBranch\CreateBankBranchService;
use SimpleBank\Domain\Model\BankBranch\BankBranchAlreadyExistsException;
use SimpleBank\Domain\Model\BankBranch\BankBranchNotExistsException;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;
use SimpleBank\Domain\Transactions;
use SimpleBank\Infrastructure\Persistence\BankBranch\InMemoryBankBranchRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateBankBranchServiceTest extends KernelTestCase
{
    use ProphecyTrait;

    private BankBranchRepositoryInterface $bankBranchRepository;
    private $transactionManager;

    public function setUp(): void {
        $this->bankBranchRepository = new InMemoryBankBranchRepository();
        $this->transactionManager = $this->prophesize(Transactions::class);
    }

    /**
     * @dataProvider getBankBranchesDto
     */
    public function testCreateBankBranchSaveShouldReturnTrue(BankBranchDto $bankBranchDto)
    {
        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->commit()
            ->shouldBeCalledOnce()
            ->willReturn(true);

        $createBankBranchService = new CreateBankBranchService(
            $this->bankBranchRepository,
            $this->transactionManager->reveal()
        );

        $this->assertTrue($createBankBranchService->save($bankBranchDto));
    }

    public function testCreateBankBranchSaveThrowsExceptionWhenEmptyBankBranchDto()
    {
        $this->expectException(BankBranchNotExistsException::class);

        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->rollBack()
            ->shouldBeCalledOnce();

        $createBankBranchService = new CreateBankBranchService(
            $this->bankBranchRepository,
            $this->transactionManager->reveal()
        );

        $createBankBranchService->save(new BankBranchDto());
    }

    public function testCreateBankBranchSaveThrowsExceptionWhenBankBranchAlreadyExists()
    {
        $this->expectException(BankBranchAlreadyExistsException::class);

        $this->transactionManager
            ->beginTransaction()
            ->shouldBeCalledTimes(2);

        $this->transactionManager
            ->commit()
            ->shouldBeCalledOnce();

        $this->transactionManager
            ->rollBack()
            ->shouldBeCalledOnce();

        $createBankBranchService = new CreateBankBranchService(
            $this->bankBranchRepository,
            $this->transactionManager->reveal()
        );

        $bankBranchDtoA =  new BankBranchDto();
        $bankBranchDtoA->setName('Hugo Money Sl');
        $bankBranchDtoA->setLocation('Zaragoza, Calle Caballo 7');

        $bankBranchDtoB =  new BankBranchDto();
        $bankBranchDtoB->setName('Hugo Money Sl');
        $bankBranchDtoB->setLocation('Zaragoza, Calle Caballo 7');

        $createBankBranchService->save($bankBranchDtoA);
        $createBankBranchService->save($bankBranchDtoB);
    }

    public function getBankBranchesDto(): array
    {
        $bankBranchDtoA =  new BankBranchDto();
        $bankBranchDtoA->setName('Hugo Money Sl');
        $bankBranchDtoA->setLocation('Zaragoza, Calle Caballo 7');

        $bankBranchDtoB =  new BankBranchDto();
        $bankBranchDtoB->setName('Pietro Money Sl');
        $bankBranchDtoB->setLocation('Milano, Via Palmanova 131');

        return [
          [
              $bankBranchDtoA
          ],
          [
              $bankBranchDtoB
          ]
        ];
    }
}
