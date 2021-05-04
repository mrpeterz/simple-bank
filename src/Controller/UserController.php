<?php

declare(strict_types=1);

namespace SimpleBank\Controller;

use SimpleBank\Application\DataTransformer\BankBranch\BankTransferDto;
use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Application\Service\BankBranch\BankBranchTransferService;
use SimpleBank\Application\Service\User\CreateUserService;
use SimpleBank\Application\Service\User\UserFinderService;
use SimpleBank\Controller\Form\UserType;
use SimpleBank\Controller\Form\WireTransferType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function add(Request $request, CreateUserService $createUserService): Response
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

            try {
                $createUserService->save($userDto);
                $this->addFlash('success', 'User created.');
            }catch (\Exception $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render('user/users_bank_branches_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(UserFinderService $userFinder): Response
    {
        $items = $userFinder->listUsers();

        return $this->render('user\users_list.html.twig', [
            'items' => $items
        ]);
    }

    public function show(Request $request, UserFinderService $userFinder): Response
    {
        $userId = $request->attributes->get('userId');
        $item = $userFinder->searchUser($userId);

        return $this->render('user\users_show.html.twig', [
            'item' => $item
        ]);
    }

    public function wire(
        Request $request,
        BankBranchTransferService $bankTransferManager,
        UserFinderService $userFinder
    ): Response {

        $fromUserId = $request->attributes->get('userId');

        $users = $userFinder->listOtherUsers($fromUserId);

        $form = $this->createForm(WireTransferType::class, $users);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $bankTransferDto = new BankTransferDto();
            $bankTransferDto->setFromUserId($fromUserId);
            $bankTransferDto->setToUserId($data['user']);
            $bankTransferDto->setAmount($data['amount']);

            try {
                $bankTransferManager->wireTransfer($bankTransferDto);
                $this->addFlash('success', 'Wire transfer done.');
            }catch (\Exception $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render('user/users_wire_transfer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
