<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Numéro de mobile ou adresse e-mail']
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Mot de passe']
            ])
            ->add('fullName', TextType::class, [
               'label' => false,
                'attr' => ['placeholder' => 'Nom complet']
            ])
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Nom de profil"]
            ])
              ->add('birthDate', BirthdayType::class, [
               'label' => 'Date de naissance',
               'years' => range(date('Y') - 100, date('Y') - 10),
               'placeholder' => [
               'day' => 'Jour',
               'month' => 'Mois',
               'year' => 'Année',
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'btn btn-primary w-100']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}