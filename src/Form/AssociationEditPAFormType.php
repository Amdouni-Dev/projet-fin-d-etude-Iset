<?php


namespace App\Form;


use App\Entity\Association;
use App\Entity\BlogPost;
use App\Entity\Categorie;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;

class AssociationEditPAFormType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;


    /**
     * BlogPostFormType constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('titre',
                TextType::class, [
                    'required' => true,
                    'label' => "Changez le nom de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('siege',
                TextType::class, [
                    'required' => true,
                    'label' => "Changez le siege de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('adresse',
                TextType::class, [
                    'required' => true,
                    'label' => "Changez l\'adresse de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('nombreMembre',
                TextType::class, [
                    'required' => true,
                    'label' => "Changez le nombre des membres de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('logo', FileType::class, [
                'label' => 'Changez le logo de votre association ',
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
            ->add('but', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "9",
                    'cols' => "45"
                ],
                'label' => "Changez le but de votre assocition "
            ])
        ;
//        if($this->security->isGranted("ROLE_EDITORIAL")){
//            $builder->add("valid",CheckboxType::class,[
//                "label"=>"Activé ?",
//                "attr"=>["class"=>"iCheck-helper"],
//                "required"=>false
//            ]);
//        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
