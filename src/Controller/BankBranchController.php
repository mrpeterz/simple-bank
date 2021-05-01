<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Application\Service\BankBranch\BankBranchFinder;
use SimpleBank\Application\Service\BankBranch\CreateBankBranch;
use SimpleBank\Controller\Form\BankBranchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BankBranchController extends AbstractController
{
    public function add(Request $request, CreateBankBranch $createUser): Response
    {
        $form = $this->createForm(BankBranchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $userDto = new BankBranchDto();
            $userDto->setName($data['name']);
            $userDto->setLocation($data['location']);

            if($createUser->save($userDto)) {
                $this->addFlash('success', 'Bank Branch Created!');
            }
        }

        return $this->render('bank_branch/bank_branches_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(BankBranchFinder $bankBranchFinder): Response
    {
        $items = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches_list.html.twig', [
            'items' => $items
        ]);
    }
}
