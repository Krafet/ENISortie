<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription/inscrire/{idSortie}", name="inscription_inscrire")
     */
    public function inscrire(int $idSortie, EntityManagerInterface $em, UserInterface $user)
    {
        $array = array(
            "id" => $idSortie
        );

        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo->find($idSortie);

        $uneInscription = new Inscription();

        $uneInscription->setParticipant($user);
        $uneInscription->setSortie($sortie);
        $uneInscription->setDateInscription(new \DateTime());

        $em->persist($uneInscription);
        $em->flush();

        $this->addFlash("success", "Vous vous êtes inscrit à la sortie ".$sortie->getNom()." !");
        return $this->redirectToRoute("sortie_afficher", $array);
    }

    /**
     * @Route("/inscription/sedesister/{idSortie}", name="inscription_seDesister")
     */
    public function seDesister(int $idSortie, EntityManagerInterface $em, UserInterface $user)
    {
        $array = array(
            "id" => $idSortie
        );

        $repoSortie = $em->getRepository(Sortie::class);
        $sortie = $repoSortie->find($idSortie);

        $repoInscription = $em->getRepository(Inscription::class);
        $inscription = $repoInscription->findByIdSortieAndIdParticipant($sortie, $user);

        $em->remove($inscription[0]);
        $em->flush();

        $this->addFlash("success", "Vous vous êtes désinscrit de la sortie !");
        return $this->redirectToRoute("home", $array);
    }
}
