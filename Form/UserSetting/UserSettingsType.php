<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Form\UserSetting;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'user_login.email',
                'required' => true,
                'mapped' => false
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'user_settings.password',
                'invalid_message' => 'users_form.password_not_matching',
                'first_options'  => ['label' => 'user_register.password'],
                'second_options' => ['label' => 'user_register.repeat_password'],
                'required' => true,
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'user_settings.submit',
                'attr' => ['class' => 'ml-auto mr-auto btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }
}
