<?php

namespace Test\Collections\Utilities\Outline;

use Fi\Collections\Utilities\Outline\Outline;
use PHPUnit\Framework\TestCase;

class OutlineTest extends TestCase
{
    public function testItInitializes(): void
    {
        $this->assertInstanceOf(Outline::class, new Outline([]));
    }

    public function testItCanNotBeInitializedEmpty(): void
    {
        $this->expectException(\TypeError::class);
        $sut = new Outline(null);
    }

    public function testItReturnNullIfEmptyArray(): void
    {
        $sut = new Outline([]);
        $this->assertNull($sut->extract(''));
    }

    public function testItCanExtractOneLevelPath(): void
    {
        $array = [
            'path' => 'value'
        ];
        $sut = new Outline($array);
        $this->assertEquals('value', $sut->extract('path'));
    }

    public function testItCanExtractTwoLevelsPath(): void
    {
        $array = [
            'path' => [
                'path2' => 'value2'
            ]
        ];
        $sut = new Outline($array);
        $this->assertEquals('value2', $sut->extract('path.path2'));
    }

    public function testItCanExtractSeveralLevelsPath(): void
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

    public function testItCanSelectElementInArray(): void
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

    public function testItCanReturnAnArray(): void
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
