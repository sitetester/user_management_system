<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\Admin\AdminUsersManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route("/users")
 */
class AdminUsersController extends AbstractController
{
    private $adminUsersManager;

    public function __construct(AdminUsersManager $adminUsersManager)
    {
        $this->adminUsersManager = $adminUsersManager;
    }

    /**
     * @Route("/", name="admin_users_list")
     */
    public function index(): Response
    {
        $users = $this->adminUsersManager->getAll();

        return $this->render('admin/users/list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/add-edit/{id}", name="admin_users_add_edit")
     */
    public function addEdit(Request $request, int $id = 0)
    {
        $user = $id > 0
            ? $this->adminUsersManager->getById($id)
            : new User();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminUsersManager->save($user);

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/add_edit.html.twig', [
            'form' => $form->createView(),
            'editMode' => $user->getId()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_users_delete")
     */
    public function delete(int $id = 0): RedirectResponse
    {
        $this->adminUsersManager->deleteById($id);

        return $this->redirectToRoute('admin_users_list');
    }
}
