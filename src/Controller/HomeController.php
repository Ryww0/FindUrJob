<?php

namespace App\Controller;

use App\Repository\ApplyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ApplyRepository $applyRepository): Response
    {
        // get user connected
        $user = $this->getUser();

        // get the applies' target of user connected
        $target = $user->getTarget();

        // get all the applies done by user connected
        $applies = $applyRepository->findAppliesByUser($user);

        // get the number of applies
        $appliesCount = count($applies);
        $appliesPercent = ($appliesCount / $target * 100);

        return $this->render('home/index.html.twig', [
            "target" => $target,
            "appliesPercent" => $appliesPercent,
            "applies" => $applies
        ]);
    }
}
