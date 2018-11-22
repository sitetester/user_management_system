<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\RoleFormType;
use App\Service\Admin\AdminRolesManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route("/roles")
 *
 * Also see `role_hierarchy` in config/packages/security.yaml
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
     * For further work, divide into separate actions
     *
     * @Route("/add-edit/{id}", name="admin_roles_add_edit")
     */
    public function addEdit(Request $request, int $id = 0)
    {
        $role = new Role();
        if ($id) {
            $role = $this->adminRolesManager->getById($id);
            if (!$role) {
                throw $this->createNotFoundException('The role does not exist');
            }
        }

        $form = $this->createForm(RoleFormType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminRolesManager->addOrUpdate($role);

            return $this->redirectToRoute('admin_roles_list');
        }

        return $this->render('admin/roles/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_roles_delete")
     */
    public function delete(Role $role): Response
    {
        $this->adminRolesManager->delete($role);

        return $this->redirectToRoute('admin_roles_list');
    }
}
