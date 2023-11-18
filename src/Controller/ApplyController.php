<?php

namespace App\Controller;

use App\Entity\Apply;
use App\Entity\Company;
use App\Form\ApplyType;
use App\Form\CompanyType;
use App\Repository\ApplyRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplyController extends AbstractController
{
    #[Route('/apply/new', name: 'app_apply_new')]
    public function addApply(Request $request, ManagerRegistry $doctrine)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($company);
            $em->flush();

            $apply = new Apply();
            $apply->setUser($this->getUser());
            $apply->setCompany($company);

            $em = $doctrine->getManager();
            $em->persist($apply);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('apply/new.html.twig', [
            "formAddApply" => $form->createView()
        ]);
    }
}
