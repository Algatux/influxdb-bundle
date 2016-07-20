<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Form\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Choice type listing available tag keys of a measurement.
 */
final class TagKeyType extends AbstractInfluxChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('measurement')
            ->setDefaults([
                'choices' => function (Options $options) {
                    $choices = $this->loadChoicesFromQuery(
                        sprintf('SHOW TAG KEYS FROM "%s"', $options['measurement']),
                        'tagKey',
                        $options['connection']
                    );

                    if ($options['exclude_host']) {
                        unset($choices['host']);
                    }

                    return $choices;
                },
                // Set to false to include the host to the tag list.
                'exclude_host' => true,
            ])
            ->setAllowedTypes('measurement', 'string')
            ->setAllowedTypes('exclude_host', 'bool')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'influx_tag_key';
    }
}
