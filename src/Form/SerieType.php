<?php

namespace App\Form;

use App\Entity\Director;
use App\Entity\Serie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('director', EntityType::class, [
                'class' => Director::class,
                'choice_label' => function(Director $director) {
                    return $director->getFullname();
                },
                'placeholder' => 'Choice a director',
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('d')
                        ->where('d.active = :active')
                        ->setParameter('active', true);
                }
            ])
            ->add('season', IntegerType::class, [
                'help' => 'Indiquer le nombre de saison de votre série'
            ])
            ->add('airingDate', DateType::class, [
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
            'required' => false
        ]);
    }
}
