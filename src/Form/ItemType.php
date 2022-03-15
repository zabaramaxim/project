<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('tag')
//            ->add('createdAt', HiddenType::class)
//            ->add('createdAt', DateTimeType::class, ['date_label' => 'date of creation', 'days' => range(1,31), 'placeholder' => 'Select a value'])
            ->add('number', IntegerType::class, ['label'=>'You can add a number such as the number of items'])
            ->add('line')
            ->add('text', TextareaType::class, ['label'=>'You can add some text about your item'] )
            ->add('date', DateType::class, ['label'=>'You can add some date', 'widget' => 'single_text'] )

            ->add('save', SubmitType::class)
            ->getForm()

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
