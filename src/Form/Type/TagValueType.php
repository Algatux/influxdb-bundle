<?php

declare(strict_types=1);

namespace Yproximite\InfluxDbBundle\Form\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Choice type listing available tag values of a measurement tag key.
 */
final class TagValueType extends AbstractInfluxChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['measurement', 'tag_key'])
            ->setDefaults([
                'choices' => function (Options $options) {
                    return $this->loadChoicesFromQuery(
                        sprintf(
                            'SHOW TAG VALUES FROM "%s" WITH KEY = "%s"',
                            $options['measurement'],
                            $options['tag_key']
                        ),
                        'value',
                        $options['connection']
                    );
                },
            ])
            ->setAllowedTypes('measurement', ['string'])
            ->setAllowedTypes('tag_key', ['string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'influx_tag_value';
    }
}
