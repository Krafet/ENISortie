<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em,
                                UserPasswordEncoderInterface $encoder)
    {

        $participant = new Participant();


        $registerForm = $this->createForm(RegisterType::class, $participant);

        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hash
                $hashedPassword = $encoder->encodePassword($participant, $participant->getMotPasse());
                $participant->setMotPasse($hashedPassword);
                $participant->setAdministrateur(false);
                $participant->setActif(false);

                $em->persist($participant);
                $em->flush();
                $this->addFlash('success', "Utilisateur ajouté");
                $this->redirectToRoute('home');
        }

        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView()
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login()
    {

        return $this->render("user/login.html.twig",
        [

        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
