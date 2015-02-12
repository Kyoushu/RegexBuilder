<?php

namespace Kyoushu\RegexBuilder\Tests;

use Kyoushu\RegexBuilder\RegexBuilder;

class RegexBuilderTest extends \PHPUnit_Framework_TestCase{

    public function testGetRegex()
    {
        $regex = RegexBuilder::create('#')
            ->start()
            ->string('foo)')->captureAs('bar')->repeated()
            ->string('bar')->optional()
            ->end()
            ->getRegex();
        ;

        $this->assertEquals('#^(?<bar>(?:foo\))+)(?:bar)?$#', $regex);

        if(!preg_match($regex, 'foo)foo)bar', $match)){
            $this->fail('Regex did not match test string');
        }

        if(!preg_match($regex, 'foo)foo)', $match)){
            $this->fail('Regex did not match test string');
        }

        $this->assertArrayHasKey('bar', $match);
        $this->assertEquals('foo)foo)', $match['bar']);

        $regex = RegexBuilder::create()
            ->letter()->repeated()->captureAs('office')
            ->string('_')
            ->number()->repeated(3)->captureAs('id')
            ->end()
            ->getRegex();

        if(!preg_match($regex, '0192_OfficeName_123', $match)){
            $this->fail('Regex did not match test string');
        }

        $this->assertArrayHasKey('office', $match);
        $this->assertArrayHasKey('id', $match);
        $this->assertEquals('OfficeName', $match['office']);
        $this->assertEquals('123', $match['id']);

    }



}