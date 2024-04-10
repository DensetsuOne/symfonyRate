<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'      => 'Почта',
                'attr'       => [
                    'class'       => 'form-control mb-3',
                    'id'          => 'floatingInput',
                    'placeholder' => 'email'
                ],
                'label_attr' => [
                    'class'  => 'form-label',
                    'for'    => 'floatingInput'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'label' => false,
                'type'  => PasswordType::class,
                'invalid_message' => 'Пароли не совпадают',
                'first_options'   => [
                    'label'       => false,
                    'attr'        => [
                        'class'       => 'form-control form-control-lg mb-3',
                        'placeholder' => 'Пароль'
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr'  => [
                        'class'       => 'form-control form-control-lg mb-3',
                        'placeholder' => 'Повторите пароль'
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}