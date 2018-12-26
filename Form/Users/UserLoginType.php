<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Form\Users;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;

	class UserLoginType extends AbstractType
	{
	    public function buildForm(FormBuilderInterface $builder, array $options)
	    {
	        $builder
	            ->add('username', TextType::class, [
	            	'label' => 'user_login.username',
	            	'required' => true,
	            	'mapped' => false
	            ])
	            ->add('password', PasswordType::class, [
	            	'label' => 'user_login.password',
				    'invalid_message' => 'users_form.password_not_matching',
				    'required' => true,
				    'mapped' => false
				])
				->add('save', SubmitType::class, [
					'label' => 'user_login.submit_btn',
					'attr' => ['class' => 'ml-auto mr-auto btn-primary']
				])
	        ;
	    }

	    public function configureOptions(OptionsResolver $resolver)
		{
		    $resolver->setDefaults(array());
		}
	}