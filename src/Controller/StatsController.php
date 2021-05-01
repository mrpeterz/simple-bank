<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\Service\Stats\StatsFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{
    public function stats(StatsFactory $statsFactory): Response
    {
        $highestBalances = $statsFactory->highestBalances();
        $topBankBranches = $statsFactory->topBankBranches();

        return $this->render('bank_branch\bank_branches_stats.html.twig', [
            'highestBalances' => $highestBalances,
            'topBankBranches' => $topBankBranches
        ]);
    }
}
