<?php

namespace App\Form;

use App\Entity\Fournisseurs;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle' , null, ['label' => 'Nom du fournisseur'])
            ->add('adresse', null, ['label' => 'Adresse'])
            ->add('codePostale', null, ['label' => 'Code Postale'])
            ->add('ville', null, ['label' => 'Ville'])
            ->add('telFixe', null, ['label' => 'Téléphone Fixe'])
            ->add('telPortable', null, ['label' => 'Téléphone Portable'])
            ->add('coordLatLng', null, ['label' => 'Coordonnées Latitude Longitude'])
            ->add('user', EntityType::class,[
                'label' => 'Utilisateur rattaché au fournisseur',
                'class' => Users::class,
                'multiple'=>false,
                'expanded' => false,
                'choice_label' => function ($user) {
                    return $user->getId().' | '.$user->getCivil(). ' '.$user->getNom().' '.$user->getPrenom();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fournisseurs::class,
        ]);
    }
}
