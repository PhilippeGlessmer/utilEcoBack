<?php

namespace App\Form;

use App\Entity\Particuliers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticuliersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, ['label' => 'Nom'])
            ->add('prenom', null, ['label' => 'Prénom'])
            ->add('email', null, ['label' => 'Email'])
            ->add('pointsCollects', null, ['label' => 'Point Collect'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Particuliers::class,
        ]);
    }
}
