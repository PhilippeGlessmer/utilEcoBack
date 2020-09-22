<?php

namespace App\Form;

use App\Entity\Pointscollects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointscollectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle' , null, ['label' => 'Nom du Point Collect'])
            ->add('adresse', null, ['label' => 'Adresse'])
            ->add('codePostal', null, ['label' => 'Code Postale'])
            ->add('ville', null, ['label' => 'Ville'])
            ->add('telFixe', null, ['label' => 'Téléphone Fixe'])
            ->add('telPortable', null, ['label' => 'Téléphone Portable'])
            ->add('coordLatLng', null, ['label' => 'Coordonnées Latitude Longitude'])
            //->add('particuliers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pointscollects::class,
        ]);
    }
}
