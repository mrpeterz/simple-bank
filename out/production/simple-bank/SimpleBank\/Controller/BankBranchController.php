<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\Service\BankBranch\BankBranchFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BankBranchController extends AbstractController
{
    public function add(Request $request, CreateUser $createUser): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bankBranchId = $request->attributes->get('bankBranchId');

            $data = $form->getData();

            $userDto = new UserDto();
            $userDto->setName($data['name']);
            $userDto->setBranchId($bankBranchId);

            if($createUser->save($userDto)) {
                $this->addFlash('success', 'User Created!');
            }
        }

        return $this->render('user/users_bank_branches_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(BankBranchFinder $bankBranchFinder): Response
    {
        $bankBranches = $bankBranchFinder->listBankBranches();

        return $this->render('bank_branch\bank_branches_list.html.twig', [
            'bankBranches' => $bankBranches
        ]);
    }
}
