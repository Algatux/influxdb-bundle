<?php

namespace Yproximite\InfluxDbBundle\Tests\unit\Form\Type;

use Yproximite\InfluxDbBundle\Form\Type\TagValueType;
use InfluxDB\ResultSet;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class TagValueTypeTest extends AbstractInfluxChoiceTypeTest
{
    public function testSubmitValidData()
    {
        $formData = 'aufs';

        $resultSet = new ResultSet(json_encode([
            'results' => [
                [
                    'series' => [
                        [
                            'name' => 'disk',
                            'columns' => [
                                'key',
                                'value',
                            ],
                            'values' => [
                                [
                                    'fstype',
                                    'ext4',
                                ],
                                [
                                    'fstype',
                                    'aufs',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));

        $this->database->expects($this->once())->method('query')
            ->with('SHOW TAG VALUES FROM "disk" WITH KEY = "fstype"')
            ->willReturn($resultSet)
        ;

        $form = $this->factory->create(TagValueType::class, null, [
            'measurement' => 'disk',
            'tag_key' => 'fstype',
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
        return new TagValueType();
    }
}
