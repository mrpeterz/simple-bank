<?php

namespace SimpleBank\Controller;

use SimpleBank\Application\User\CreateUser;
use SimpleBank\Controller\Form\UserType;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserId;
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

            $user = new User(
                new UserId(),
                $data['name'],
                new BankBranchId($bankBranchId)
            );

            if($createUser->save($user)) {
                $this->addFlash('success', 'User Created!');
            }
        }

        return $this->render('user/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
