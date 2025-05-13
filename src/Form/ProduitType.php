<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>
            'fw-bold']])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => 'nomMarque', 
                'placeholder' => 'Choisissez une marque', 
                'attr' => ['class' => 'form-control'], 
                'label_attr' => ['class' => 'fw-bold'], ])
            ->add('prix', NumberType::class,  ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>
            'fw-bold']])
            ->add('Description', TextareaType::class,  ['attr' => ['class'=> 'form-control', 'rows'=>'7', 'cols'
            => '7'], 'label_attr' => ['class'=> 'fw-bold']])
            ->add('stock', NumberType::class,  ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>
            'fw-bold']])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomCategorie', 
                'placeholder' => 'Choisissez une categorie', 
                'attr' => ['class' => 'form-control'], 
                'label_attr' => ['class' => 'fw-bold'], ])
            ->add('image', FileType::class, array('label' => 'Image', 'mapped'=>false,'attr' => ['class'=>
            'form-control'], 'label_attr' => ['class'=> 'fw-bold'],'constraints' => [
            new File([
            'mimeTypes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/jpg',
            ],
            'mimeTypesMessage' => 'Le site accepte les fichiers PDF, PNG , JPG , WEBP',
            ])
            ],))
            ->add('envoyer', SubmitType::class, ['attr' => ['class'=> 'btn bg-primary text-white m-4' ],
            'row_attr' => ['class' => 'text-center'],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
