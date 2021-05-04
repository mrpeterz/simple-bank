<?php

declare(strict_types=1);

namespace SimpleBank\Controller;

use SimpleBank\Application\Service\BankBranch\BankBranchStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{
    public function stats(BankBranchStatsService $bankBranchStatsService): Response
    {
        $highestBalances = $bankBranchStatsService->highestBalances();
        $topBankBranches = $bankBranchStatsService->topBankBranches();

        return $this->render('bank_branch\bank_branches_stats.html.twig', [
            'highestBalances' => $highestBalances,
            'topBankBranches' => $topBankBranches
        ]);
    }
}
