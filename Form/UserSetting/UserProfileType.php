<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Form\UserSetting;

use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class UserLoginType
 * @package ReaccionEstudio\ReaccionCMSBundle\Form\Users
 */
class UserProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'user_settings.nickname',
                'required' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'user_settings.submit',
                'attr' => ['class' => 'mx-auto btn-primary']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }
}
