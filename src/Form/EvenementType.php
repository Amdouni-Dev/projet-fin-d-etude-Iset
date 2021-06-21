<?php

namespace App\Form;

use App\Entity\Evenement;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet',TextType::class,['label'=>'Modifier le sujet de votre evenement'])
            ->add('description',TextareaType::class,['label'=>'Modifiez la description de votre evenement'])
//            ->add('image')
            ->add('image', FileType::class, [
                'label' => 'Merci de recharger  l\image de votre evenement ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('place',TextType::class,['label'=>'Modifier la localisation d\'evenement'])
            ->add('date',DateTimeType::class,['label'=>'Modifier la date d\'evenement'])
            ->add('dateFinEvenement',DateTimeType::class,['label'=>'Modifier la date fin  d\'evenement'])

            ->add('finDateAnnonce',DateType::class,['label'=>'Modifier la date de fin d\'annonce d\'evenemnt'])
//            ->add('association')
//            ->add('user')
//        ;
   ; }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
