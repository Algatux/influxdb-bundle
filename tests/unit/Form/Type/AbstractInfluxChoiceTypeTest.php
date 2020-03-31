<?php

namespace Yproximite\InfluxDbBundle\Tests\unit\Form\Type;

use Yproximite\InfluxDbBundle\Form\Type\AbstractInfluxChoiceType;
use Yproximite\InfluxDbBundle\Services\ConnectionRegistry;
use InfluxDB\Database;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

abstract class AbstractInfluxChoiceTypeTest extends TypeTestCase
{
    /**
     * @var Database|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $database;

    /**
     * @var ConnectionRegistry
     */
    private $connectionRegistry;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);

        $this->connectionRegistry = new ConnectionRegistry('default');
        $this->connectionRegistry->addConnection('default', 'http', $this->database);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = $this->getInfluxChoiceTypeInstance();
        $type->setConnectionRegistry($this->connectionRegistry);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @return AbstractInfluxChoiceType
     */
    abstract protected function getInfluxChoiceTypeInstance();
}
