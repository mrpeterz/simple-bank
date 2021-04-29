<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\User\CreateUser;
use SimpleBank\Controller\Form\UserType;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function add(Request $request, CreateUser $createUser): Response
    {
        $bankBranchId = $request->query->get('myParam');

        $form = $this->createForm(UserType::class, $bankBranchId);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $user = new User(
                new UserId(),
                $data['name'],
                $data['bankBranchId']
            );

            $createUser->save($user);
        }

        return $this->render('user/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
