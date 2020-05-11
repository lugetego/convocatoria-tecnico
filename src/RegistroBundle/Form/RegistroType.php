<?php

namespace RegistroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'required'=>true,
            ))
            ->add('paterno', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'required'=>true,
            ))
            ->add('materno', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'required'=>false,
            ))

            ->add('direccion', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'required'=>true,
            ))
            ->add('mail', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
                'required'=>true,
            ))
            ->add('solicitudFile', 'vich_file', array(
                'required'      => true,
                'label' => '*Carta Solicitud'
            ))
            ->add('cvFile', 'vich_file', array(
                'required'      => true,
                'label' => '*Currículum Vitae'
            ))
            ->add('comprobanteFile', 'vich_file', array(
                'required'      => true,
                'label' => '*Comprobante oficial de grado'
            ))
            ->add('cursoFile', 'vich_file', array(
                'required'      => true,
                'label' => 'Comprobantes de cursos, certificaciones, experiencia laboral y portafolio de trabajos en los que ha participado. '
            ))

            ->add('ref1recomFile', 'vich_file', array(
                'required'      => true,
                'label' => 'Carta de Recomendación 1',
                'allow_delete'  => false,
            ))

            ->add('ref2recomFile', 'vich_file', array(
                'required'      => true,
                'label' => 'Carta de Recomendación 2'
            ))
            ->add('activo')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RegistroBundle\Entity\Registro'
        ));
    }
}
