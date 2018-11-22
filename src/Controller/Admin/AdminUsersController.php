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
 *
 * Also see `role_hierarchy` in config/packages/security.yaml
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
     * @Route("/add", name="admin_users_add")
     */
    public function add(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminUsersManager->addUser($user);

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/add_edit.html.twig', [
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_users_edit")
     */
    public function edit(Request $request, int $id = 0)
    {
        $user = $this->adminUsersManager->getById($id);
        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminUsersManager->addOrUpdate($user);

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/add_edit.html.twig', [
            'form' => $form->createView(),
            'editMode' => true
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_users_delete")
     */
    public function delete(User $user): RedirectResponse
    {
        $this->adminUsersManager->deleteUser($user);

        return $this->redirectToRoute('admin_users_list');
    }

    /**
     * @Route("/disable/{id}/{status}", name="admin_users_disable", requirements={
     *         "status": "1|0"})
     */
    public function disable(User $user, bool $status): RedirectResponse
    {
        $user->setDisabled($status);
        $this->adminUsersManager->addOrUpdate($user);

        return $this->redirectToRoute('admin_users_list');
    }
}
