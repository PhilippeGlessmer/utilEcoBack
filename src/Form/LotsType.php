<?php

namespace App\Form;

use App\Entity\Fournisseurs;
use App\Entity\Lots;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LotsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fournisseur', EntityType::class,[
                'label' => 'Selectionnez le fournisseur',
                'class' => Fournisseurs::class,
                'multiple'=>false,
                'expanded' => false,
                'choice_label' => function ($user) {
                    return $user->getLibelle();
                }
            ])
            ->add('etat', null, ['label' => 'Lot cloturé'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lots::class,
        ]);
    }
}
