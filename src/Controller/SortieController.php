<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_index")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);

        $sorties = $repo->findAll();


        return $this->render("sortie/index.html.twig", ["sorties" => $sorties]);
    }
}
