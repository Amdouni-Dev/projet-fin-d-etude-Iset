<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewTopicType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        

        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'role' => $resolver
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
            $builder
                ->add('name', 
                    TextType::class, [
                    'required' => true,
                    'label' => 'Tapez votre sujet à poser',
                    'attr' => ['class' => 'form-control']
                ])

                ->add('content', TextareaType::class, [
                    'attr' => [
                        'class' => 'form-control first-message',
                        'rows' => "9", 
                        'cols' => "45"
                    ],
                    'required' => true,
                    'label' => 'Ecrivez un message'
                ])

               

                ->add('Envoyer', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                ])
            ;
        
 
    }
}
