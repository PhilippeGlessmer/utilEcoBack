<?php

namespace App\Form;

use App\Entity\Fournisseurs;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add(
                'roles', ChoiceType::class, [
                    'choices' => ['Administrateur' => 'ROLE_ADMIN', 'Utilisateur' => 'ROLE_USER', 'Fournisseur' => 'ROLE_FOURNISSEUR'],
                    'expanded' => true,
                    'required'   => true,
                    'multiple' => true,
                ]
            )
            ->add('civil')
            ->add('nom')
            ->add('Prenom')
            ->add('telPortable')
            ->add('tauxHoraire')
            ->add('isVerified')
            ->add('fournisseurs', EntityType::class,[
                'class' => Fournisseurs::class,
                'multiple'=>false,
                'expanded' => false,
                'choice_label' => function ($user) {
                    return $user->getLibelle();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
