<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Nom'))
            ->add('surname', TextType::class, array('label' => 'Prénom'))
            ->add('birthDate', DateType::class, array('label' => 'Date de Naissance'))
            ->add('nationality', TextType::class, array('label' => 'Nationalité'))
            ->add('tarifRed', checkboxType::class , array(
                'label' => ' à cocher si vous avez droit à un tarif réduit ( Justificatif obligatoire à l\'entrée)',
                'required' => false,
            ));

    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sabate_louvrebundle_ticket';
    }


}
