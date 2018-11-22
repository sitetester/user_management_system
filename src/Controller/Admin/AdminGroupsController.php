<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Form\GroupFormType;
use App\Service\Admin\AdminGroupsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * `/admin` prefix already applied in /config/packages/routing.yaml
 * @Route("/groups")
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
     * @Route("/add-edit/{id}", name="admin_groups_add_edit")
     */
    public function addEdit(Request $request, int $id = 0)
    {
        $group = $id > 0 ?
            $this->adminGroupsManager->getById($id) :
            new Group();

        $form = $this->createForm(GroupFormType::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminGroupsManager->save($group);

            return $this->redirectToRoute('admin_groups_list');
        }

        return $this->render('admin/groups/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_groups_delete")
     */
    public function delete(int $id = 0): Response
    {
        $group = $this->adminGroupsManager->getById($id);
        if (!$group->getUsers()->isEmpty()) {
            throw new \Exception('Group can\'t be deleted with assigned users.');
        }

        $this->adminGroupsManager->deleteGroup($group);

        return $this->redirectToRoute('admin_groups_list');
    }
}
