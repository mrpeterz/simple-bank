<?php

namespace SimpleBank\Controller;

use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    private BankBranchRepositoryInterface $bankBranchRepository;

    public function __construct(BankBranchRepositoryInterface $bankBranchRepository)
    {
        $this->bankBranchRepository = $bankBranchRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $bankBranch = new BankBranch(
            new BankBranchId(),
            'Intesa San Paolo',
            'Via dei morti di fame 10, Milano' . rand(0,1000)
        );

        $this->bankBranchRepository->save($bankBranch);

        $branchId = new BankBranchId('d47b1933-1eab-4117-8c27-951542d6a73c');
        $bankBranchB = $this->bankBranchRepository->search($branchId);

        $data = $this->bankBranchRepository->all();

        return $this->json(json_encode($data), 200);
    }
}
