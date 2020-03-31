<?php

namespace Yproximite\InfluxDbBundle\Tests\unit\Form\Type;

use Yproximite\InfluxDbBundle\Form\Type\FieldKeyType;
use InfluxDB\ResultSet;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class FieldKeyTypeTest extends AbstractInfluxChoiceTypeTest
{
    public function testSubmitValidData()
    {
        $formData = 'usage_guest';

        $resultSet = new ResultSet(json_encode([
            'results' => [
                [
                    'series' => [
                        [
                            'name' => 'cpu',
                            'columns' => [
                                'time',
                                'fieldKey',
                            ],
                            'values' => [
                                [
                                    '1970-01-01T00:00:00Z',
                                    'usage_guest',
                                ],
                                [
                                    '1970-01-01T00:00:00Z',
                                    'usage_idle',
                                ],
                                [
                                    '1970-01-01T00:00:00Z',
                                    'usage_nice',
                                ],
                                [
                                    '1970-01-01T00:00:00Z',
                                    'usage_iowait',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));

        $this->database->expects($this->once())->method('query')
            ->with('SHOW FIELD KEYS FROM "cpu"')
            ->willReturn($resultSet)
        ;

        $form = $this->factory->create(FieldKeyType::class, null, [
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
        $this->assertCount(4, $view->vars['choices'], 'The form must have 4 choices');
    }

    /**
     * {@inheritdoc}
     */
    protected function getInfluxChoiceTypeInstance()
    {
        return new FieldKeyType();
    }
}
