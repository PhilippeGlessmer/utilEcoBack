<?php

namespace App\Form;

use App\Entity\Distributeurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributeursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',  null, ['label' => 'Nom du Distributeur',])
            ->add('adresse',  null, ['label' => 'Adresse',])
            ->add('codePostale',  null, ['label' => 'Code Postale',])
            ->add('ville' ,  null, ['label' => 'Ville',])
            ->add('telFixe' ,  null, ['label' => 'Téléphone Fixe',])
            ->add('telPortable',  null, ['label' => 'Téléphone Portable',])
            ->add('CoordLatLng',  null, ['label' => 'Coordonnées Latitude Longitude',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Distributeurs::class,
        ]);
    }
}
