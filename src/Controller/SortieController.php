<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreateSortieType;
use App\Form\ModifierSortieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request, EntityManagerInterface $em, UserInterface $user)
    {
        $repoLieu = $em->getRepository(Lieu::class);
        $lieux = $repoLieu->findAll();

        $repoVille = $em->getRepository(Ville::class);
        $villes = $repoVille->findAll();

        $createSortieForm = $this->createForm(CreateSortieType::class);

        if($user != null)
        {
            $createSortieForm->get("campus")->setData($user->getCampus()->getNom());
        }

        $createSortieForm->handleRequest($request);

        if($createSortieForm->isSubmitted() && $createSortieForm->isValid())
        {
            $arrayForm = $request->get("create_sortie");

            $repoLieu = $em->getRepository(Lieu::class);
            $lieu = $repoLieu->find($arrayForm["lieu"]);

            $repoEtat = $em->getRepository(Etat::class);
            $etat = $repoEtat->find(1);

            $sortie = new Sortie();

            $dateHeureDebut = new \DateTime($arrayForm["dateHeureDebut"]);
            $dateLimiteInscription = new \DateTime($arrayForm["dateLimiteInscription"]);

            $sortie->setNom($arrayForm["nom"]);
            $sortie->setDateHeureDebut($dateHeureDebut);
            $sortie->setDateLimiteInscription($dateLimiteInscription);
            $sortie->setNbInscriptionsMax(intval($arrayForm["nbInscriptionsMax"]));
            $sortie->setDuree(intval($arrayForm["duree"]));
            $sortie->setInfosSortie($arrayForm["infosSortie"]);
            $sortie->setSiteOrganisateur($user->getCampus());
            $sortie->setLieu($lieu);
            $sortie->setIsPrivate(0);
            $sortie->setOrganisateur($user);
            $sortie->setEtat($etat);


            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "La sortie à été correctement ajoutée !");
            return $this->redirectToRoute("home");
        }

        return $this->render("sortie/create.html.twig", [
            "lieux"=>$lieux,
            "villes"=>$villes,
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

        $repoLieu = $em->getRepository(Lieu::class);
        $lieux = $repoLieu->findAll();

        $repoVille = $em->getRepository(Ville::class);
        $villes = $repoVille->findAll();

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
            $arrayForm = $request->get("modifier_sortie");

            $repoSortie = $em->getRepository(Sortie::class);
            $sortie = $repoSortie->find($id);

            $repoCampus = $em->getRepository(Campus::class);
            $campus = $repoCampus->find($arrayForm['campus']);

            $repoLieu = $em->getRepository(Lieu::class);
            $lieu = $repoLieu->find($arrayForm["lieu"]);

            $repoEtat = $em->getRepository(Etat::class);
            $etat = $repoEtat->find($sortie->getEtat());

            $dateHeureDebut = new \DateTime($arrayForm["dateHeureDebut"]);
            $dateLimiteInscription = new \DateTime($arrayForm["dateLimiteInscription"]);

            $sortie->setNom($arrayForm['nom']);
            $sortie->setDateHeureDebut($dateHeureDebut);
            $sortie->setDateLimiteInscription($dateLimiteInscription);
            $sortie->setNbInscriptionsMax($arrayForm['nbInscriptionsMax']);
            $sortie->setDuree($arrayForm['duree']);
            $sortie->setInfosSortie($arrayForm['infosSortie']);
            $sortie->setSiteOrganisateur($campus);
            $sortie->setLieu($lieu);
            $sortie->setIsPrivate(0);
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "La sortie à été correctement modifiée !");
            return $this->redirectToRoute("home");
        }

        return $this->render("sortie/modifier.html.twig", [
            "sortie"=> $sortie,
            "lieux"=>$lieux,
            "villes"=>$villes,
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

    /**
     * @Route("/sortie/delete/{id}", name="sortie_delete", requirements={"id": "\d+"})
     */
    public function delete(int $id, Request $request, EntityManagerInterface $em)
    {
        $repoSortie = $em->getRepository(Sortie::class);
        $sortie = $repoSortie->find($id);

        $em->remove($sortie);
        $em->flush();

        $this->addFlash("success", "La sortie à été correctement supprimée !");
        return $this->redirectToRoute("home");

    }

    /**
     * @Route("/sortie/publier/{id}", name="sortie_publier", requirements={"id": "\d+"})
     */
    public function publier(int $id, Request $request, EntityManagerInterface $em)
    {
        $repoSortie = $em->getRepository(Sortie::class);
        $sortie = $repoSortie->find($id);

        $repoEtat = $em->getRepository(Etat::class);
        $etat = $repoEtat->find(2);

        $sortie->setEtat($etat);
        $sortie->setMotifAnnulation(null);

        $em->persist($sortie);
        $em->flush();

        $this->addFlash("success", "La sortie à été correctement publiée !");
        return $this->redirectToRoute("home");
    }
}
