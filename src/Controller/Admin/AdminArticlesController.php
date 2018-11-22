<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Service\Admin\AdminArticlesManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Dummy controller, just to check logged in user permission (ROLE)
 *
 * @IsGranted("ROLE_EDITOR")
 * @Route("/articles")
 */
class AdminArticlesController extends AbstractController
{
    private $adminArticlesManager;

    public function __construct(AdminArticlesManager $adminArticlesManager)
    {
        $this->adminArticlesManager = $adminArticlesManager;
    }

    /**
     * @Route("/", name="admin_articles_list")
     */
    public function index()
    {
        return $this->render('admin/articles/list.html.twig', [
            'articles' => $this->adminArticlesManager->getAll()
        ]);
    }

    /**
     * @Route("/add-edit/{id}", name="admin_articles_add_edit")
     */
    public function addEdit(Request $request, int $id = 0): Response
    {
        $article = $id > 0 ?
            $this->adminArticlesManager->getById($id) :
            new Article();

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminArticlesManager->save($article);

            return $this->redirectToRoute('admin_articles_list');
        }

        return $this->render('admin/articles/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_articles_delete")
     */
    public function delete(int $id = 0): Response
    {
        $this->adminArticlesManager->deleteById($id);

        return $this->redirectToRoute('admin_articles_list');
    }
}
