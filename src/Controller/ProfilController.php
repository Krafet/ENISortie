<?php

namespace App\Controller;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function index($id, EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Participant::class);


        $user = $repository->find($id);

        dump($user);

        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
            'participant' => $user
        ]);
    }
}
