<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\DataTransformer\BankBranch\BankTransferDto;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Application\Service\BankBranch\BankTransferManager;
use SimpleBank\Application\Service\User\CreateUser;
use SimpleBank\Application\Service\User\UserFinder;
use SimpleBank\Controller\Form\UserType;
use SimpleBank\Controller\Form\WireTransferType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
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
            $userDto->setBalance($data['balance']);
            $userDto->setBankBranchId($bankBranchId);

            if(!$createUser->save($userDto)) {
                $this->addFlash('error', 'Problem with User creation.');
            }else{
                $this->addFlash('success', 'User created.');
            }
        }

        return $this->render('user/users_bank_branches_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(UserFinder $userFinder): Response
    {
        $items = $userFinder->listUsers();

        return $this->render('user\users_list.html.twig', [
            'items' => $items
        ]);
    }

    public function show(Request $request, UserFinder $userFinder): Response
    {
        $userId = $request->attributes->get('userId');
        $item = $userFinder->searchUsers($userId);

        return $this->render('user\users_show.html.twig', [
            'item' => $item
        ]);
    }

    public function wire(
        Request $request,
        BankTransferManager $bankTransferManager,
        UserFinder $userFinder
    ): Response {

        $fromUserId = $request->attributes->get('userId');

        $users = $userFinder->listOtherUsers($fromUserId);

        $form = $this->createForm(WireTransferType::class, $users);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $bankTransferDto = new BankTransferDto();
            $bankTransferDto->setFromUserId($fromUserId);
            $bankTransferDto->setToUserId($data['toUserId']);
            $bankTransferDto->setAmount($data['amount']);

            if(!$bankTransferManager->wireTransfer($bankTransferDto)) {
                $this->addFlash('error', 'Problem with wire transfer.');
            }else{
                $this->addFlash('success', 'Wire transfer done.');
            }
        }

        return $this->render('user/users_wire_transfer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
