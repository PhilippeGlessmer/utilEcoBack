<?php

namespace App\Form;

use App\Entity\Proccess;
use App\Entity\Types;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProccessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class,[
                'class' => Types::class,
                'label' => 'Type d\'appareil',
                'placeholder' => 'Selectionnez le type de la procédure',

                'choice_label' => function ($marque) {
                    return $marque->getlibelle();
                }
            ])
            ->add('action', null, [
                'label' => 'Action a exécuter',
            ])
            ->add(
                'resultNegatif', ChoiceType::class, [
                    'label' => 'Résultat négatif',
                    'choices' => ['' => null , 'A donner' => -2, 'A jeter' => -1],
                ]
            )
            ->add('imageFile', VichFileType::class, [
                'label' => 'Fichier image',
                'required' => false
            ])
           // ->add('resultPositif')
            ->add('ordre', IntegerType::class,[
                'required'   => false,

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proccess::class,
        ]);
    }
}
