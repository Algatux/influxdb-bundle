<?php

declare(strict_types=1);

namespace Yproximite\InfluxDbBundle\Form\Type;

use Yproximite\InfluxDbBundle\Services\ConnectionRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Abstract class to get access to the InfluxDB connections registry.
 */
abstract class AbstractInfluxChoiceType extends AbstractType
{
    /**
     * @var ConnectionRegistry
     */
    protected $connectionRegistry;

    /**
     * @param ConnectionRegistry $connectionRegistry
     */
    final public function setConnectionRegistry(ConnectionRegistry $connectionRegistry)
    {
        $this->connectionRegistry = $connectionRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('connection', null)
            ->setAllowedTypes('connection', ['string', 'null'])
        ;

        // To be removed when bumping symfony/form constraint to version 3.1+
        if (!in_array(DataTransformerInterface::class, class_implements(TextType::class))) {
            $resolver->setDefault('choices_as_values', true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * Executes the given query and converts the result to a proper choices list.
     *
     * @param string      $query
     * @param string      $columnName
     * @param string|null $connectionName
     *
     * @return array
     */
    final protected function loadChoicesFromQuery(string $query, string $columnName, string $connectionName = null)
    {
        $connection = $connectionName
            ? $this->connectionRegistry->getHttpConnection($connectionName)
            : $this->connectionRegistry->getDefaultHttpConnection()
        ;
        $measurements = array_map(function ($point) use ($columnName) {
            return $point[$columnName];
        }, $connection->query($query)->getPoints());

        return array_combine(array_values($measurements), $measurements);
    }
}
