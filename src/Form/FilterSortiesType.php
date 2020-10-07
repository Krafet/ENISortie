<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,

                'choice_label' => 'nom',
                'label'=> 'Campus',
                'required'   => false,
            ])
            ->add('nomSortie', TextType::class,[
                'label'=> 'Le nom de la sortie contient :',
                'required'   => false,
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label'=> 'Entre',
                'required'   => false,
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label'=> 'et',
                'required'   => false,
            ])
            ->add('cbOrginisateur', CheckboxType::class, [
                'label'=> 'Sorties dont je suis l\'organisateur/trice',
                'required'   => false,
            ])
            ->add('cbInscrit', CheckboxType::class, [
                'label'=> 'Sorties auxquelles je suis inscrit/e',
                'required'   => false,
            ])
            ->add('cbNonInscrit', CheckboxType::class, [
                'label'=> 'Sorties auxquelles je ne suis pas inscrit/e',
                'required'   => false,
            ])
            ->add('cbSortiePassee', CheckboxType::class, [
                'label'=> 'Sorties pasées',
                'required'   => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
