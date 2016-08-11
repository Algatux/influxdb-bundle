<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Form\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Choice type listing available measurements.
 */
final class MeasurementType extends AbstractInfluxChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    return $this->loadChoicesFromQuery(
                        'SHOW MEASUREMENTS',
                        'name',
                        $options['connection']
                    );
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'influx_measurement';
    }
}
