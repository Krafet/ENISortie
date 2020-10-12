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
            'editProfil' => false,
            'edit' => false,
            'editPassword' => false,
            'controller_name' => 'ProfilController',
            'participant' => $user
        ]);
    }



    /**
     * @Route("/profil/edit", name="profilEdit")
     */
    public function profil_edit(Request $request, EntityManagerInterface $em)
    {
        $participant = new Participants();
        $participant = $this->getUser();

        $form = $this->createForm(ParticipantsType::class, $participant);
        $form->remove('motDePasse')
            ->remove('campus')
            ->remove('submit');
        $form->add('submit',SubmitType::class, [
            'label' => 'Mettre à jour',
            'attr' => [
                'class' => 'btn btn-success w-100'
            ]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $participant = new Participants();
            $participant = $form->getData();

            $em->persist($participant);
            $em->flush();
            $this->addFlash('success','Le profil a été mis à jour !');

            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/profil.html.twig', [
            'editProfil' => true,
            'edit' => false,
            'editPassword' => false,
            'form' => $form->createView(),
            'page_name' => 'Profil'
        ]);
    }
}
