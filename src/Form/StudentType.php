<?php

namespace App\Form;

use App\Entity\Information;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('class', ChoiceType::class, [
                'choices' =>[
                    '10th' => '10th',
                    '9th' => '9th',
                    '8th' => '8th',
                    '7th' => '7th',
                ]
            ])
            ->add('subjects', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Subject1' => 'Sub1',
                    'Subject2' => 'Sub2',
                    'Subject3' => 'Sub3',
                ],
                'attr' => [
                    'class' => 'form-check-inline'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'attr' => [
                    'class' => 'form-check-inline'
                ]
            ])
            ->add('address', TextareaType::class)
            ->add('photoName', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('Add',SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-primary btn-lg btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Information::class,
        ]);
    }
}
