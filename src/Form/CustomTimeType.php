<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CustomTimeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->generateTimeChoices(),
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    private function generateTimeChoices()
    {
        $choices = [];

        $startTime = new \DateTime('09:00');
        $endTime = new \DateTime('19:00');

        while ($startTime <= $endTime) {
            $choices[$startTime->format('H:i')] = $startTime->format('H:i');
            $startTime->modify('+30 minutes');
        }

        return $choices;
    }
}
