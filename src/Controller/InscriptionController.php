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

        if($sortie->getDateLimiteInscription() > new \DateTime())
        {
            if(count($sortie->getInscriptions()) < $sortie->getNbInscriptionsMax())
            {
                $em->persist($uneInscription);
                $em->flush();
                $this->addFlash("success", "Vous vous êtes inscrit à la sortie ".$sortie->getNom()." !");
            }
            else
            {
                $this->addFlash("warning", "Nombres de places maximum atteint. Inscription à la sortie non effectuée.");
                return $this->redirectToRoute("home");
            }
        }
        else
        {
            $this->addFlash("warning", "La date limite d'inscription à été atteint. Inscription à la sortie non effectuée.");
            return $this->redirectToRoute("home");
        }
        return $this->redirectToRoute("sortie_afficher", $array);
    }

    /**
     * @Route("/inscription/sedesister/{idSortie}", name="inscription_seDesister")
     */
    public function seDesister(int $idSortie, EntityManagerInterface $em, UserInterface $user)
    {
        $repoSortie = $em->getRepository(Sortie::class);
        $sortie = $repoSortie->find($idSortie);

        $repoInscription = $em->getRepository(Inscription::class);
        $inscription = $repoInscription->findByIdSortieAndIdParticipant($sortie, $user);

        if($sortie->getDateHeureDebut() > new \DateTime())
        {
            $em->remove($inscription[0]);
            $em->flush();
            $this->addFlash("success", "Vous vous êtes désinscrit de la sortie !");
        }
        else
        {
            $this->addFlash("warning", "La sortie à déjà débuter, vous ne pouvez pas vous désinscrire !");
            return $this->redirectToRoute("home");
        }

        return $this->redirectToRoute("home");
    }
}
