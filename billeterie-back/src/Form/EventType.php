<?php
namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('imageEvent', TextType::class)
            ->add('eventHour', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'html5' => false,  // Désactiver l'option html5
                'format' => 'HH:mm:ss',
            ])
            ->add('bookingDate', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'html5' => false,  // Désactiver l'option html5
                'format' => 'yyyy-MM-dd',
            ])
            ->add('eventDate', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'html5' => false,  // Désactiver l'option html5
                'format' => 'yyyy-MM-dd\'T\'HH:mm:ss',
            ])
            ->add('type', TextType::class)
            ->add('description', TextType::class)
            ->add('cancel', CheckboxType::class)
            ->add('nbTicket', IntegerType::class)
            ->add('soldTickets', IntegerType::class)
            ->add('isSoldOut', CheckboxType::class)
            ->add('isAdult', CheckboxType::class)
            ->add('isGuestAdult', CheckboxType::class)
            ->add('location', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'csrf_protection' => false, // Désactiver la protection CSRF pour les endpoints API
        ]);
    }
}
