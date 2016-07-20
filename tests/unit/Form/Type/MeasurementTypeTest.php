<?php

namespace Algatux\InfluxDbBundle\Tests\unit\Form\Type;

use Algatux\InfluxDbBundle\Form\Type\MeasurementType;
use InfluxDB\ResultSet;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class MeasurementTypeTest extends AbstractInfluxChoiceTypeTest
{
    public function testSubmitValidData()
    {
        $formData = 'cpu';

        $resultSet = new ResultSet(json_encode([
            'results' => [
                [
                    'series' => [
                        [
                            'name' => 'measurement',
                            'columns' => [
                                'name',
                            ],
                            'values' => [
                                [
                                    'cpu',
                                ],
                                [
                                    'mem',
                                ],
                                [
                                    'disk',
                                ],
                                [
                                    'apache',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));

        $this->database->expects($this->once())->method('query')
            ->with('SHOW MEASUREMENTS')
            ->willReturn($resultSet)
        ;

        $form = $this->factory->create(MeasurementType::class);

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
        return new MeasurementType();
    }
}
