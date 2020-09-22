<?php

namespace App\Form;

use App\Entity\Fournisseurs;
use App\Entity\Lots;
use App\Repository\LotsRepository;
use App\Entity\Marques;
use App\Entity\Materiels;
use App\Entity\Models;
use App\Entity\Particuliers;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MaterielsType extends AbstractType
{
    private $lotsRepository;

    public function __construct(LotsRepository $lotsRepository)
    {
        $this->lotsRepository = $lotsRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('marque', EntityType::class, [
                'label' => 'Marque',
                'class' => Marques::class,
                'placeholder' => 'Selectionnez la marque de l\'appareil',
                'mapped' => false,
                'required' => false
            ])
        ->add('modele', ChoiceType::class,[
            'label' => 'Modèle',
            'placeholder' => 'Selectionnez le modèle',
        ])
        ->add('nSerieFabricant',  null, ['label' => 'Numéro de série du Fabricant'])
        ->add('nSerieFournisseur', null, ['label' => 'Numéro de série du Fournisseur'])
        ->add('lots', EntityType::class, [
            'label' => 'Lot n°',
            'class' => Lots::class,
            'placeholder' => 'Selectionnez le lot de l\'appareil',

            'required' => false,
            'choices' => $this->lotsRepository->findLotEtat('0')
        ])
        ;
        $builder->get('marque')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                $form->getParent()->add('modele', EntityType::class,[
                    'class'=> Models::class,
                    'placeholder' => 'Selectionnez le modèle',
                    'choices' => $form->getData()->getModels()

                ]);
            }
        );
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!empty($data['itemNew']['name'])) {
                $form->remove('item');

                $form->add('itemNew', new Particuliers(), array(
                    'required' => TRUE,
                    'mapped' => TRUE,
                    'property_path' => 'particuliers',
                ));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiels::class,
        ]);
    }
}
