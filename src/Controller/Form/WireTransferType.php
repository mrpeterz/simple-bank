<?php

namespace SimpleBank\Controller\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class WireTransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['data']) {
            return;
        }

        $builder
            ->add('user', ChoiceType::class, ['choices' => $options['data']])
            ->add('amount', NumberType::class)
            ->add('Save', SubmitType::class);
    }
}
