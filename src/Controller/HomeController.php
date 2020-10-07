<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\FilterSortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $campus = "";
        $dateDebut = "";
        $filterSortieForm = $this->createForm(FilterSortiesType::class);

        $filterSortieForm->handleRequest($request);
        if($filterSortieForm->isSubmitted() && $filterSortieForm->isValid())
        {
            $campus = $filterSortieForm['campus']->getData();
            $nomSortie = $filterSortieForm['nomSortie']->getData();
            $dateDebut = $filterSortieForm['dateDebut']->getData();
            $dateFin = $filterSortieForm['dateFin']->getData();
            $cbOrginisateur = $filterSortieForm['cbOrginisateur']->getData();
            $cbInscrit = $filterSortieForm['cbInscrit']->getData();
            $cbNonInscrit = $filterSortieForm['cbNonInscrit']->getData();
            $cbSortiePassee = $filterSortieForm['cbSortiePassee']->getData();


        }

        return $this->render("sortie/index.html.twig", [
            "filterSortieForm"=> $filterSortieForm->createView(),
        ]);
    }
}
