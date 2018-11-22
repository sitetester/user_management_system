<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Form\GroupFormType;
use App\Service\Admin\AdminGroupsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route("/groups")
 *
 * Also see role_hierarchy in config/packages/security.yaml
 */
class AdminGroupsController extends AbstractController
{
    private $adminGroupsManager;

    public function __construct(AdminGroupsManager $adminGroupsManager)
    {
        $this->adminGroupsManager = $adminGroupsManager;
    }

    /**
     * @Route("/", name="admin_groups_list")
     */
    public function index(): Response
    {
        return $this->render('admin/groups/list.html.twig', [
            'groups' => $this->adminGroupsManager->getAll()
        ]);
    }

    /**
     * For further work, divide into separate actions
     *
     * @Route("/add-edit/{id}", name="admin_groups_add_edit")
     */
    public function addEdit(Request $request, int $id = 0)
    {
        $group = new Group();
        if ($id) {
            $group = $this->adminGroupsManager->getById($id);
            if (!$group) {
                throw $this->createNotFoundException('The group does not exist');
            }
        }

        $form = $this->createForm(GroupFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminGroupsManager->addOrUpdate($group);

            return $this->redirectToRoute('admin_groups_list');
        }

        return $this->render('admin/groups/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_groups_delete")
     */
    public function delete(Group $group): Response
    {
        if (!$group->getUsers()->isEmpty()) {
            throw new \UnexpectedValueException('Group can\'t be deleted with assigned users.');
        }

        $this->adminGroupsManager->delete($group);

        return $this->redirectToRoute('admin_groups_list');
    }
}
