<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\Service\BankBranch\BankBranchStats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{
    public function stats(BankBranchStats $statsFactory): Response
    {
        $highestBalances = $statsFactory->highestBalances();
        $topBankBranches = $statsFactory->topBankBranches();

        return $this->render('bank_branch\bank_branches_stats.html.twig', [
            'highestBalances' => $highestBalances,
            'topBankBranches' => $topBankBranches
        ]);
    }
}
