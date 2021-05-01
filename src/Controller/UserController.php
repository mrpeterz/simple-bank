<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\DataTransformer\User\UserDto;
use SimpleBank\Application\Service\User\CreateUser;
use SimpleBank\Application\Service\User\UserFinder;
use SimpleBank\Controller\Form\UserType;
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
            $userDto->setBranchId($bankBranchId);

            if($createUser->save($userDto)) {
                $this->addFlash('success', 'User Created!');
            }
        }

        return $this->render('user/users_bank_branches_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(UserFinder $userFinder): Response
    {
        $users = $userFinder->listUsers();

        return $this->render('user\users_list.html.twig', [
            'users' => $users
        ]);
    }

    public function show(Request $request, UserFinder $userFinder): Response
    {
        $userId = $request->attributes->get('userId');

        $user = $userFinder->searchUsers($userId);

        return $this->render('user\users_show.html.twig', [
            'user' => $user
        ]);
    }
}
