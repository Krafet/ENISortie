<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=> 'Nom de la sortie :',
                'required'   => true,
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                'widget' => 'single_text',
                'label'=> 'Date et heure de la sortie :',
                'required'   => true,
            ])
            ->add('dateLimiteInscription', DateTimeType::class,[
                'widget' => 'single_text',
                'label'=> 'Date limite d\'inscription :',
                'required'   => true,
            ])
            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=> 'Nombre de places :',
                'attr'=> ['min' => 0],
                'required'   => true,
            ])
            ->add('duree', IntegerType::class,[
                'label'=> 'Durée :',
                'attr'=> ['min' => 0],
                'required'   => true,
            ])
            ->add('infosSortie', TextareaType::class, [
                'label'=> 'Description et infos :',
                'required'   => true,
            ])
            ->add('campus', TextType::class, [
                'label'=> 'Campus :',
                'required' => true,
                'disabled' => true,
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => function(Lieu $lieu){
                return $lieu->getNom(). ' - '.$lieu->getVille()->getNom();
                },
                'label'=> 'Lieu :',
                //'attr'=> ['onchange'=> 'remplirChamps()'],
                'required'   => false,
            ])

            ->add('rue', TextType::class, [
                'label'=> 'Rue :',
                'required' => true,
            ])
            ->add('codePostal', TextType::class, [
                'label'=> 'Code postal :',
                'required' => true,
            ])
            ->add('latitude', TextType::class, [
                'label'=> 'Latitude :',
                'required' => true,
            ])
            ->add('longitude', TextType::class, [
                'label'=> 'Longitude :',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'data_class' => Sortie::class,
//        ]);
    }
}
