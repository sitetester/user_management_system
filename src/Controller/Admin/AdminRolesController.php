<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\RoleFormType;
use App\Service\Admin\AdminRolesManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roles")
 */
class AdminRolesController extends AbstractController
{
    private $adminRolesManager;

    public function __construct(AdminRolesManager $adminRolesManager)
    {
        $this->adminRolesManager = $adminRolesManager;
    }

    /**
     * @Route("/", name="admin_roles_list")
     */
    public function index(): Response
    {
        return $this->render('admin/roles/list.html.twig', [
            'roles' => $this->adminRolesManager->getAll()
        ]);
    }

    /**
     * @Route("/add-edit/{id}", name="admin_roles_add_edit")
     */
    public function addEdit(Request $request, int $id = 0)
    {
        $role = $id > 0
            ? $this->adminRolesManager->getById($id)
            : new Role();

        $form = $this->createForm(RoleFormType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminRolesManager->save($role);

            return $this->redirectToRoute('admin_roles_list');
        }

        return $this->render('admin/roles/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_roles_delete")
     */
    public function delete(int $id): Response
    {
        $this->adminRolesManager->deleteById($id);

        return $this->redirectToRoute('admin_roles_list');
    }
}
