<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Form\Users;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;

	class UserRegisterType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('username', TextType::class, [
	            	'label' => 'user_register.username',
	            	'required' => true,
	            	'mapped' => false
	            ])
	            ->add('email', EmailType::class, [
	            	'label' => 'user_register.email',
	            	'required' => true,
	            	'mapped' => false
	            ])
	            ->add('password', RepeatedType::class, [
				    'type' => PasswordType::class,
				    'invalid_message' => 'users_form.password_not_matching',
				    'options' => array('attr' => array('class' => 'password-field')),
				    'required' => true,
				    'first_options'  => [ 'label' => 'user_register.password' ],
				    'second_options' => [ 'label' => 'user_register.repeat_password' ],
				    'mapped' => false
				])
				->add('save', SubmitType::class, [
					'label' => 'user_register.submit_btn',
					'attr' => ['class' => 'ml-auto mr-auto btn-primary']
				])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array());
		}
	}