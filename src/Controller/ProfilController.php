<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfilController extends AbstractController
{


    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('profil/profil.html.twig', [
            'editProfil' => false,
            'editPassword' => false,
            'form' => null,
            'page_name' => 'Profil'
        ]);
    }



    /**
     * @Route("/profil/{id}", name="profilID")
     */
    public function profilID(int $id, EntityManagerInterface $em)
    {
        $participant = new Participant();
        $participant = $em->getRepository(Participant::class)->find($id);

        return $this->render('profil/other.html.twig', [
            'participant' => $participant
        ]);
    }


    /**
     * @Route("/profil/edit", name="profilEdit")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profilEdit(Request $request, EntityManagerInterface $em)
    {


        $participant = new Participant();
        $participant = $this->getUser();


        $form = $this->createForm(RegisterType::class, $participant);
        $form->remove('motDePasse');
        $form->add('submit',SubmitType::class, [
            'label' => 'Mettre à jour',
            'attr' => [
                'class' => 'btn btn-success w-100'
            ]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $participant = new Participant();
            $participant = $form->getData();



            $participant->setAdministrateur(false);
            $participant->setActif(false);

            $em->persist($participant);
            $em->flush();
            $this->addFlash('success','Le profil a été mis à jour !');

            return $this->redirectToRoute('profil');
        }


        return $this->render('profil/profil.html.twig', [
            'editProfil' => true,
            'editPassword' => false,
            'form' => $form->createView(),
            'page_name' => 'Profil'
        ]);
    }
}
