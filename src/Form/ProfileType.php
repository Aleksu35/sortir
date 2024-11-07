<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'disabled' => true,
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom', 'required' => false
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom', 'required' => false
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone', 'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email', 'disabled' => true
            ])
            ->add('plainPassword', RepeatedType::class, [

                'type' => PasswordType::class,
                'required' => false,
                'mapped' => false,
                'first_options' => ['attr' => ['placeholder' => 'Enter Your New Password']],
                'second_options' => ['attr' => ['placeholder' => 'Repeat Your Password']],
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'class' => Campus::class,
                'choice_label' => 'nom',
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5024k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg'],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
                'label' => 'Upload images',
            ])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler'])

            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $participant = $event->getData();
                $plainPassword = $form->get('plainPassword')->getData();

                if ($plainPassword) {
                    $passwordHasher = $options['passwordHasher'];
                    $hashedPassword = $passwordHasher->hashPassword($participant, $plainPassword);
                    $participant->setPassword($hashedPassword);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'passwordHasher' => null
        ]);
    }
}
