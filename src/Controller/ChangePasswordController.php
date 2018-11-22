<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\Model\ChangePassword;
use App\Service\ChangePasswordManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends Controller
{
    private $changePasswordManager;

    public function __construct(ChangePasswordManager $changePasswordManager)
    {
        $this->changePasswordManager = $changePasswordManager;
    }

    /**
     * @Route("/change_password", name="app_change_password")
     * @return Response
     */
    public function changePasswordAction(Request $request): Response
    {
        // usually you'll want to make sure the user is authenticated first
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, new ChangePassword());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ChangePassword $changePasswordModel */
            $changePasswordModel = $form->getData();
            if ($this->changePasswordManager->changePassword($user, $changePasswordModel->getPassword())) {
                $this->addFlash(
                    'notice',
                    'Password updated successfully!'
                );
            }
        }

        // dispatch event and confirm listener is getting invoked

        return $this->render('changePassword/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
