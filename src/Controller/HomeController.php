<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\FilterSortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $em, UserInterface $user)
    {
        $repo = $em->getRepository(Sortie::class);
        $sorties = $repo->findAllSortie();
        $filterSortieForm = $this->createForm(FilterSortiesType::class);

        $filterSortieForm->handleRequest($request);
        if($filterSortieForm->isSubmitted() && $filterSortieForm->isValid())
        {
            $filterArray = Array(
                'campus' =>  $filterSortieForm['campus']->getData(),
                'nomSortie' => $filterSortieForm['nomSortie']->getData(),
                'dateDebut' => $filterSortieForm['dateDebut']->getData(),
                'dateFin' => $filterSortieForm['dateFin']->getData(),
                'cbOrganisateur' => $filterSortieForm['cbOrginisateur']->getData(),
                'cbInscrit' =>$filterSortieForm['cbInscrit']->getData(),
                'cbNonInscrit' => $filterSortieForm['cbNonInscrit']->getData(),
                'cbSortiePassee' => $filterSortieForm['cbSortiePassee']->getData(),
                'user' => $user,
            );


            $sorties = $repo->findByFilter($filterArray);

            return $this->render("sortie/index.html.twig", [
                "filterSortieForm"=> $filterSortieForm->createView(),
                "sorties" => $sorties,
            ]);
        }

        return $this->render("sortie/index.html.twig", [
            "filterSortieForm"=> $filterSortieForm->createView(),
            "sorties" => $sorties,
        ]);
    }
}
