<?php

namespace sabate\LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVisit', DateType::class)
            ->add('nbrTicket')
            ->add('email')
            ->add('typeTicket', choiceType::class, array(
                'choices' => array(
                    'Journée' => 'Journée',
                    'Demi-journée' => 'Demi-journée'
                ),
            ))
            ->add('save',  SubmitType::class, array('label' => ' Suivant '));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'sabate\LouvreBundle\Entity\Booking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sabate_louvrebundle_booking';
    }


}
