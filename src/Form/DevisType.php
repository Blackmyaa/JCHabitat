<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // 🔹 Informations personnelles
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir votre nom.'])],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new NotBlank(['message' => 'Veuillez saisir votre prénom.'])],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [new NotBlank(['message' => 'Veuillez indiquer un numéro de téléphone.'])],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer une adresse e-mail.']),
                    new Email(['message' => 'Adresse e-mail invalide.'])
                ],
            ])
            ->add('pays', ChoiceType::class, [
                'label' => 'Pays',
                'multiple' => false,
                'choices' => [
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                ],
                'placeholder' => '--Sélectionnez votre pays--',
                'constraints' => [new NotBlank(['message' => 'Veuillez sélectionner un pays.'])],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [new NotBlank(['message' => 'Veuillez indiquer votre ville.'])],
            ])

            // 🔹 Type d’intervention
            ->add('type_intervention', ChoiceType::class, [
                'label' => 'Type d’intervention',
                'multiple' => false,
                'choices' => [
                    'Remplacement d’un cumulus existant' => 'remplacement',
                    'Installation d’un nouveau cumulus' => 'installation',
                ],
                'placeholder' => '--Sélectionnez le type d’intervention--',
                'constraints' => [new NotBlank(['message' => 'Veuillez préciser le type d’intervention.'])],
            ])

            // 🔹 Données techniques
            ->add('capacite', ChoiceType::class, [
                'label' => 'Capacité du chauffe-eau',
                'multiple' => false,
                'choices' => [
                    '50 L' => '50L',
                    '100 L' => '100L',
                    '150 L' => '150L',
                    '200 L' => '200L',
                    '300 L' => '300L',
                ],
                'placeholder' => '--Sélectionnez la capacité souhaitée--',
                'constraints' => [new NotBlank(['message' => 'Veuillez choisir une capacité.'])],
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'Position de l’appareil',
                'multiple' => false,
                'choices' => [
                    'Vertical mural' => 'vertical_mural',
                    'Horizontal mural' => 'horizontal_mural',
                    'Sur trépied / au sol' => 'au_sol',
                ],
                'placeholder' => '--Sélectionnez une position--',
            ])
            ->add('accessibilite', ChoiceType::class, [
                'label' => 'Accessibilité du lieu',
                'multiple' => false,
                'choices' => [
                    'Facile (rez-de-chaussée, accès direct)' => 'Facile',
                    'Moyenne (1er étage, escalier large)' => 'Moyenne',
                    'Difficile (étage élevé ou accès restreint)' => 'Difficile',
                ],
                'placeholder' => '--Évaluez l’accessibilité--',
            ])

            // 🔹 Ancien matériel (si remplacement)
            ->add('ancien_modele', TextType::class, [
                'label' => 'Ancien modèle / marque (si remplacement)',
                'required' => false,
            ])

            // // 🔹 Upload photo (max 3 fichiers)
            // ->add('photos', FileType::class, [
            //     'label' => 'Photos (maximum 3)',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'required' => false,
            //     'constraints' => [
            //         new Count([
            //             'max' => 3,
            //             'maxMessage' => 'Vous pouvez envoyer au maximum 3 photos.',
            //         ]),
            //         new File([
            //             'maxSize' => '5M',
            //             'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
            //             'mimeTypesMessage' => 'Seules les images JPEG, PNG ou WEBP sont autorisées.',
            //         ]),
            //     ],
            // ])

            // 🔹 Message complémentaire
            ->add('message', TextareaType::class, [
                'label' => 'Informations complémentaires',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}