<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Employee;
use App\Entity\Reservation;
use App\Form\CustomTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // A faire plus tard
            // Calendrier des disponibilités selon le coiffeur choisie ou toutes les disponibilités si indifférent choisi dans coiffeur
            ->add('date', DateType::class,[
                'label' => 'Date : ',
                'widget' => 'single_text',
                'required' => true,
            ])

            // A faire plus tard
            // Heures suivant la date et la disponibilité du coiffeur choisie ou toutes les disponibilités si indifférent choisi dans coiffeur
            ->add('hour', CustomTimeType::class, [
                'label' => 'Heure : ',
                'required' => true,
            ])

            ->add('employee', EntityType::class, [
                'label' => 'Coiffeur : ',
                'class' => Employee::class,
                'choice_label' => fn(Employee $employee) => $employee->getLastName() . ' ' . $employee->getFirstName(),
                'placeholder' => 'Indiférrent',
                'required' => true,
            ])

            ->add('services', EntityType::class, [
                'label' => 'Service : ',
                'class' => Service::class,
                'choice_label' => fn(Service $service) => $service->getCategory() . ' - ' . $service->getName(),
                'placeholder' => 'Choisissez un service',
                'required' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Réserver',
                'attr' => ['class' => 'btn btn-outline-light btn-lg px-5'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
