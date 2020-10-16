<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{


    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        $participant = new Participant();
        $participant = $this->getUser();


        return $this->render('profil/profil.html.twig', [
            'participant' => $participant,
            'editProfil' => false,
            'editPassword' => false,
            'form' => null,
            'page_name' => 'Profil',
            'other' => false,
        ]);
    }



    /**
     * @Route("/profil/{id}", name="profilID", requirements={"id"="\d+"})
     */
    public function profilID(int $id, EntityManagerInterface $em)
    {
        $participant = new Participant();
        $participant = $em->getRepository(Participant::class)->find($id);

        if($participant->getUsername() == $this->getUser()->getUsername())
        {
            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/profil.html.twig', [
            'participant' => $participant,
            'editProfil' => false,
            'editPassword' => false,
            'other' => true,
        ]);
    }


    /**
     * @Route("/profil/edit", name="profilEdit")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profilEdit(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
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

            $hashedPassword = $encoder->encodePassword($participant, $participant->getMotPasse());
            $participant->setMotPasse($hashedPassword);

            $em->persist($participant);
            $em->flush();
            $this->addFlash('success','Le profil a été mis à jour !');

            return $this->redirectToRoute('profil');
        }


        return $this->render('profil/profil.html.twig', [
            'participant' => $participant,
            'editProfil' => true,
            'editPassword' => false,
            'form' => $form->createView(),
            'page_name' => 'Profil',
            'other' => false,
        ]);
    }
}
