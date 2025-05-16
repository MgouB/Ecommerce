<?php

namespace App\Form;

use App\Entity\Marque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ModifierMarqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomMarque', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']])
        ->add('imageMarque', FileType::class, array('label' => 'Image', 'mapped' => false, 'attr' => ['class' =>
        'form-control'], 'label_attr' => ['class' => 'fw-bold'], 'constraints' => [
        new File([
            'mimeTypes' => [
                'image/jpeg',
                'image/webp',
                'image/png',
            ],
            'mimeTypesMessage' => 'Le site accepte uniquement les fichiers webp, png et jpg',
        ]),
    ]))
    ->add('Ajouter', SubmitType::class, ['attr' => ['class' => 'btn bg-primary text-white m-4'],
        'row_attr' => ['class' => 'text-center']])
;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marque::class,
        ]);
    }
}
