<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\BankBranch\BankBranchFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index(BankBranchFinder $bankBranchFinder)
    {
        $bankBranches = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches.html.twig', [
            'bankBranches' => $bankBranches
        ]);
    }
}
