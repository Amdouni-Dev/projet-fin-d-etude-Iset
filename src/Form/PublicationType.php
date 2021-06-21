<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('date_pub')
            ->add('contenu_pub',TextareaType::class,['required'=>true])
//            ->add('nom',null,['required'=>true])
//            ->add('age',IntegerType::class,['required'=>true])
//            ->add('sexe',ChoiceType::class,['required'=>true,'choices' => array(
//        'Femme' => 'Femme',
//        '   Homme' => 'Homme'),
//            'multiple'=>false,'expanded'=>true])
//         ->add('mutimedia',FileType::class,['required'=>true,'multiple'=>true])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
