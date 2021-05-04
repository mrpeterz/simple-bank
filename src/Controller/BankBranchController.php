<?php

declare(strict_types=1);

namespace SimpleBank\Controller;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Application\Service\BankBranch\BankBranchFinderService;
use SimpleBank\Application\Service\BankBranch\CreateBankBranchService;
use SimpleBank\Controller\Form\BankBranchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BankBranchController extends AbstractController
{
    public function add(Request $request, CreateBankBranchService $createBankBranchService): Response
    {
        $form = $this->createForm(BankBranchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $bankBranchDto = new BankBranchDto();
            $bankBranchDto->setName($data['name']);
            $bankBranchDto->setLocation($data['location']);

            try {
                $createBankBranchService->save($bankBranchDto);
                $this->addFlash('success', 'Bank Branch created.');
            }catch (\Exception $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render('bank_branch/bank_branches_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(BankBranchFinderService $bankBranchFinder): Response
    {
        $items = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches_list.html.twig', [
            'items' => $items
        ]);
    }
}
