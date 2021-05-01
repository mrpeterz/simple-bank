<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\Service\BankBranch\BankBranchFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BankBranchController extends AbstractController
{
    public function list(BankBranchFinder $bankBranchFinder): Response
    {
        $bankBranches = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches_list.html.twig', [
            'bankBranches' => $bankBranches
        ]);
    }
}
