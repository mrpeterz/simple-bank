<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\BankBranch\BankBranchFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    private BankBranchFinder $bankBranchFinder;

    public function __construct(BankBranchFinder $bankBranchFinder)
    {
        $this->bankBranchFinder = $bankBranchFinder;
    }

    public function index()
    {
        $bankBranch = $this->bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches.html.twig', [
            'bank_branches' => $bankBranch
        ]);
    }
}
