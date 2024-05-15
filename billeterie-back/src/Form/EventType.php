<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Schedule;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('imageEvent')
            ->add('eventHour', null, [
                'widget' => 'choice',
                'input' => 'datetime',
            ])
            ->add('bookingDate', null, [
                'widget' => 'single_text',
            ])
            ->add('eventDate', null, [
                'widget' => 'single_text',
            ])
            ->add('type')
            ->add('description')
            ->add('cancel')
            ->add('nbTicket')
            ->add('soldTickets')
            ->add('isSoldOut')
            ->add('isAdult')
            ->add('isGuestAdult')
            ->add('location')
            // ->add('idUser', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('schedule', EntityType::class, [
            //     'class' => Schedule::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
