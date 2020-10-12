<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\CreateSortieType;
use App\Form\ModifierSortieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $createSortieForm = $this->createForm(CreateSortieType::class);

        $createSortieForm->handleRequest($request);
        if($createSortieForm->isSubmitted() && $createSortieForm->isValid())
        {

        }

        return $this->render("sortie/create.html.twig", [
            "createSortieForm"=> $createSortieForm->createView(),
        ]);
    }

    /**
     * @Route("/sortie/afficher/{id}", name="sortie_afficher", requirements={"id": "\d+"})
     */
    public function afficher(int $id, Request $request, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo->find($id);

        return $this->render("sortie/afficher.html.twig", [
            "sortie"=> $sortie,
        ]);

    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier", requirements={"id": "\d+"})
     */
    public function modifier(int $id, Request $request, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo->find($id);

        $modifierSortieForm = $this->createForm(ModifierSortieType::class);

        if($sortie != null)
        {
            $modifierSortieForm->get("nom")->setData($sortie->getNom());
            $modifierSortieForm->get("dateHeureDebut")->setData($sortie->getDateHeureDebut());
            $modifierSortieForm->get("dateLimiteInscription")->setData($sortie->getDateLimiteInscription());
            $modifierSortieForm->get("nbInscriptionsMax")->setData(intval($sortie->getNbInscriptionsMax()));
            $modifierSortieForm->get("duree")->setData(intval($sortie->getDuree()));
            $modifierSortieForm->get("infosSortie")->setData($sortie->getInfosSortie());
            $modifierSortieForm->get("campus")->setData($sortie->getSiteOrganisateur());
            $modifierSortieForm->get("lieu")->setData($sortie->getLieu());
            $modifierSortieForm->get("rue")->setData($sortie->getLieu()->getRue());
            $modifierSortieForm->get("codePostal")->setData($sortie->getLieu()->getVille()->getCodePostal());
            $modifierSortieForm->get("latitude")->setData($sortie->getLieu()->getLatitude());
            $modifierSortieForm->get("longitude")->setData($sortie->getLieu()->getLongitude());
        }


        $modifierSortieForm->handleRequest($request);
        if($modifierSortieForm->isSubmitted() && $modifierSortieForm->isValid())
        {

            return $this->render("sortie/modifier.html.twig", [
                "sortie"=> $sortie,
                "modifierSortieForm"=> $modifierSortieForm->createView(),
            ]);
        }

        return $this->render("sortie/modifier.html.twig", [
            "sortie"=> $sortie,
            "modifierSortieForm"=> $modifierSortieForm->createView(),
        ]);
    }

    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler", requirements={"id": "\d+"})
     */
    public function annuler(int $id, Request $request, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo->find($id);

        $motif = $request->get("motif");

        if($motif != "")
        {
            $repoEtat = $em->getRepository(Etat::class);
            $etat = $repoEtat->find(6);

            $sortie->setEtat($etat);
            $sortie->setMotifAnnulation($motif);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "La sortie à été correctement annulée !");
            return $this->redirectToRoute("home");
        }

        return $this->render("sortie/annuler.html.twig", [
            "sortie"=> $sortie,
        ]);

    }
}
