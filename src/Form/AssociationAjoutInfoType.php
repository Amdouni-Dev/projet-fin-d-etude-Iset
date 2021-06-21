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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;

class AssociationAjoutInfoType extends AbstractType
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
//
//            ->add('budget',
//                MoneyType::class, [
//                    'required' => true,
//                    'label' => "ajouter le budget de votre association",
//                    'attr' => ['class' => 'form-control']
//                ])
            ->add('domaineAssociation',
                TextType::class, [
                    'required' => true,
                    'label' => "ajouter le domaine de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('siteWeb',
                TextType::class, [
                    'required' => false,
                    'label' => "Tapez votre site web",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('facebook',
                TextType::class, [
                    'required' => false,
                    'label' => "Tapez votre lien facebook",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('instagram',
                TextType::class, [
                    'required' => false,
                    'label' => "Tapez votre lien instagram",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('twitter',
                TextType::class, [
                    'required' => false,
                    'label' => "Tapez votre lien twitter",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('dateFondation',
                DateType::class, [

                    'label' => "ajouter la date de creation de votre association"
                   
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
