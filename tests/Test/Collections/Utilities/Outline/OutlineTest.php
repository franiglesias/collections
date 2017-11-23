<?php

namespace Test\Collections\Utilities\Outline;

use Fi\Collections\Utilities\Outline\Outline;
use PHPUnit\Framework\TestCase;

class OutlineTest extends TestCase
{
    public function test_It_initializes()
    {
        $this->assertInstanceOf(Outline::class, new Outline([]));
    }

    public function test_it_can_not_be_initialized_empty()
    {
        $this->expectException(\TypeError::class);
        new Outline();
    }

    public function test_it_return_null_if_empty_array()
    {
        $sut = new Outline([]);
        $this->assertNull($sut->extract(''));
    }

    public function test_it_can_extract_one_level_path()
    {
        $array = [
            'path' => 'value'
        ];
        $sut = new Outline($array);
        $this->assertEquals('value', $sut->extract('path'));
    }

    public function test_it_can_extract_two_levels_path()
    {
        $array = [
            'path' => [
                'path2' => 'value2'
            ]
        ];
        $sut = new Outline($array);
        $this->assertEquals('value2', $sut->extract('path.path2'));
    }

    public function test_it_can_extract_several_levels_path()
    {
        $array = [
            'path' => [
                'path2' => 'value2',
                'path3' => [
                    'path4' => [
                        'path5' => 'theValue'
                    ]
                ]
            ]
        ];
        $sut = new Outline($array);
        $this->assertEquals('theValue', $sut->extract('path.path3.path4.path5'));
    }

    public function test_it_can_select_element_in_array()
    {
        $array = [
            'path' => 'value',
            'path2' => [
                ['path3' => [
                    'value3'
                ]],
                ['path3'],
                ['path3']
            ]
        ];
        $sut = new Outline($array);
        $this->assertEquals(['value3'], $sut->extract('path2.0.path3'));
    }

    public function test_it_can_return_an_array()
    {
        $array = [
            'path' => 'value',
            'path2' => [
                ['path3' => [
                    'value3'
                ]],
                ['path3'],
                ['path3']
            ]
        ];
        $sut = new Outline($array);
        $this->assertEquals([
            ['path3' => ['value3']],
            ['path3'],
            ['path3']
        ], $sut->extract('path2'));
    }
}
