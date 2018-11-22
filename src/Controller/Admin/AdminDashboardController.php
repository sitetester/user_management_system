<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * `/admin` route prefix already applied in config/routes/annotations.yaml
 *
 * @IsGranted("ROLE_ADMIN")
 * @Route("/dashboard")
 */
class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
