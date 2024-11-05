<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $participant = $options['data'];

        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'data' =>  $participant->getPseudo(),
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'data' => $participant->getPrenom(),
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'data' =>  $participant->getNom(),
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'data' =>  $participant->getTelephone(),
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'data' => $participant->getEmail(),
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => false,
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'Confirmation',
                'mapped' => false,
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
                'label' => 'Upload images',
            ])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $participant = $event->getData();
            if(  $participant &&   $participant->getFilename()) {
                $form = $event->getForm();
                $form->add('deleteImage',CheckboxType::class,[
                    'mapped'=>false,
                    'required'=>false,
                ]);
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
