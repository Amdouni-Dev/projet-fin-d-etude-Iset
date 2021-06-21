<?php

namespace App\Form;

use App\Entity\Mutimedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class MultimediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('source',FileType::class,['mapped'=>false,'multiple'=>true,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                    ])
                ],
                'attr'=>[
                    'accept' => 'image/*',
                    'style'=>'border:none;background-color:transparent;type:hidden;padding;15px','class'=>'form-control ;']])
//            ->add('publication')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mutimedia::class,
        ]);
    }
}
