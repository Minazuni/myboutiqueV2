<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder
        ->add('addresses',EntityType::class,[
            'class' => Address::class,
            'label'=>'Choisissez une adresse',
            'choices'=> $options['user']->getAddresses(), 
            'multiple' => false,
            'expanded' => true,
            'required'=>true

        ])

        ->add('transporteurs',EntityType::class,[
            'class' => Carrier::class,
            'label'=>'Choisissez un transporteur',
            'choice_label'=> function (Carrier $carrier) {
                return $carrier->getName().' '.$carrier->getPrice().' €';
            },
            'multiple' => false,
            'expanded' => true,
            'required'=>true

        ])
        ->add('submit',SubmitType::class,['label'=>'Valider la commande','attr'=>['class'=>'btn btn-success col-12 ']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user'=>null
        ]);
    }
}
