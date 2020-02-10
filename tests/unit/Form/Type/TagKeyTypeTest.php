<?php

namespace Algatux\InfluxDbBundle\Tests\unit\Form\Type;

use Algatux\InfluxDbBundle\Form\Type\TagKeyType;
use InfluxDB\ResultSet;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class TagKeyTypeTest extends AbstractInfluxChoiceTypeTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $resultSet = new ResultSet(json_encode([
            'results' => [
                [
                    'series' => [
                        [
                            'name' => 'cpu',
                            'columns' => [
                                'tagKey',
                            ],
                            'values' => [
                                [
                                    'cpu',
                                ],
                                [
                                    'host',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));

        $this->database->expects($this->once())->method('query')
            ->with('SHOW TAG KEYS FROM "cpu"')
            ->willReturn($resultSet)
        ;
    }

    public function testSubmitValidData()
    {
        $formData = 'cpu';

        $form = $this->factory->create(TagKeyType::class, null, [
            'measurement' => 'cpu',
            'connection' => 'default',
        ]);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue(
            $form->isSynchronized(),
            $form->getTransformationFailure()
                ? $form->getTransformationFailure()->getMessage()
                : 'The form should be synchronized'
        );
        $this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $this->assertCount(1, $view->vars['choices'], 'The form must have 1 choices');
    }

    public function testSubmitValidDataIncludingHost()
    {
        $formData = 'host';

        $form = $this->factory->create(TagKeyType::class, null, [
            'measurement' => 'cpu',
            'connection' => 'default',
            'exclude_host' => false,
        ]);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue(
            $form->isSynchronized(),
            $form->getTransformationFailure()
                ? $form->getTransformationFailure()->getMessage()
                : 'The form should be synchronized'
        );
        $this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $this->assertCount(2, $view->vars['choices'], 'The form must have 2 choices');
    }

    /**
     * {@inheritdoc}
     */
    protected function getInfluxChoiceTypeInstance()
    {
        return new TagKeyType();
    }
}
