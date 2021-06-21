<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePwsdFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add("justpassword", PasswordType::class, [
                "label" =>"Entrez votre mot de passe actuelle",
                "required" => true,
                "mapped" => false,
                "constraints" => [
                    new NotBlank(["message" => "le champs ne doit pas etre vide"]),
                    new UserPassword(["message" => "votre mot de passe ets invalide"])
                ]
            ])
            ->add("newpassword", RepeatedType::class, [
                "mapped" => false,
                'invalid_message' => 'le mot de passe doit etre le meme',
                "type" => PasswordType::class,
                "constraints" => [
                    new NotBlank(["message" =>" le mot de passe ne doit pas etre vide"])
                ],
                "first_options"  => ['label' => "nouveau mdp"],
                "second_options"  => ['label' => "confirmer votre mdp"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
