<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\Service\BankBranch\BankBranchFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BankBranchController extends AbstractController
{
    public function show(BankBranchFinder $bankBranchFinder): Response
    {
        $bankBranches = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branch.html.twig', [
            'bankBranches' => $bankBranches
        ]);
    }
}
