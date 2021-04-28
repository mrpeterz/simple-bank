<?php

namespace SimpleBank\Controller;

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


        return $this->json("[{'ciao': 'bau'}]", 200);
    }
}
