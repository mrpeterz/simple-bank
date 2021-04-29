<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\BankBranch\BankBranchFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BankBranchController extends AbstractController
{
    public function show(BankBranchFinder $bankBranchFinder): Response
    {
        $bankBranches = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches.html.twig', [
            'bankBranches' => $bankBranches
        ]);
    }

    public function add()
    {

    }
}
