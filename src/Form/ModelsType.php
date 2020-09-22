<?php

namespace App\Form;

use App\Entity\Models;
use App\Entity\Types;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ModelsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class,[
                'class' => Types::class,
                'label' => 'Type de matériel',
                'choice_label' => function ($user) {
                    return $user->getlibelle();
                }
            ])
            ->add('libelle', null, [
                'label' => 'Nom du produit de ce modèle'
            ])
            ->add('marque', null , [
                'label' => 'Marque du modèle'
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'label' => 'Image du modèle'
            ])
            ->add('piloteFile', VichFileType::class, [
                'required' => false,
                'label' => 'Pilote du modèle'

            ])
            ->add('noticeFile', VichFileType::class, [
                'required' => false,
                'label' => 'Notice du modèle'

            ])
            ->add('prixNeuf', null,[
                'label' => 'Prix Neuf'
            ])
            ->add('prixRecon', null,[
                'label' => 'Prix Reconditionné'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Models::class,
        ]);
    }
}
