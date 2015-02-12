<?php

namespace Kyoushu\RegexBuilder\Tests;

use Kyoushu\RegexBuilder\RegexBuilder;

class RegexBuilderTest extends \PHPUnit_Framework_TestCase{

    public function testStartEnd()
    {
        $regex = RegexBuilder::create()->start()->getRegex();
        $this->assertEquals('/^/', $regex);

        $regex = RegexBuilder::create()->end()->getRegex();
        $this->assertEquals('/$/', $regex);

        $regex = RegexBuilder::create()->entireString()->getRegex();
        $this->assertEquals('/^$/', $regex);
    }

    public function testDelimiter()
    {
        $regex = RegexBuilder::create()->string('/')->getRegex();
        $this->assertEquals('/(?:\\/)/', $regex);

        $regex = RegexBuilder::create('#')->string('/')->getRegex();
        $this->assertEquals('#(?:/)#', $regex);
    }

    public function testPattern()
    {
        $regex = RegexBuilder::create()->pattern('[a-zA-Z]')->getRegex();
        $this->assertRegExp($regex, 'g');
        $this->assertNotRegExp($regex, '1');
    }

    public function testString()
    {
        $regex = RegexBuilder::create()->string('(foo)bar')->getRegex();
        $this->assertRegExp($regex, '(foo)bar');
        $this->assertNotRegExp($regex, 'foobar');
    }

    public function testCapture()
    {
        $regex = RegexBuilder::create()->string('foo')->captureAs('bar')->getRegex();
        $this->assertEquals('/(?<bar>(?:foo))/', $regex);

        if(!preg_match($regex, 'foobar', $match)){
            $this->fail('Regex did not match test string');
        }

        $this->assertArrayHasKey('bar', $match);
        $this->assertEquals('foo' ,$match['bar']);
    }

    public function testOptional()
    {
        $regex = RegexBuilder::create()->string('foo')->optional()->getRegex();

        $this->assertRegExp($regex, 'foobar');
        $this->assertRegExp($regex, 'bar');
    }

    public function testLetter()
    {
        $regex = RegexBuilder::create()->letter()->getRegex();

        $this->assertRegExp($regex, 'Hello');
        $this->assertNotRegExp($regex, '_');
        $this->assertNotRegExp($regex, '123');

    }

    public function testNumber()
    {
        $regex = RegexBuilder::create()->number()->getRegex();
        $this->assertRegExp($regex, '1');
        $this->assertNotRegExp($regex, 'a');
    }

    public function testAnything()
    {
        $regex = RegexBuilder::create()->anything()->getRegex();
        $this->assertRegExp($regex, '4');
        $this->assertRegExp($regex, 'g');
        $this->assertRegExp($regex, ' ');
        $this->assertRegExp($regex, '@');

    }

    public function testBackreference()
    {
        $regex = RegexBuilder::create()
            ->start()
            ->letter()->repeated()->captureAs('bar')
            ->string('_')
            ->matchCaptured('bar')
            ->end()
            ->getRegex();

        if(!preg_match($regex, 'foo_foo')){
            $this->fail('Regex did not match test string');
        }

        if(preg_match($regex, 'foo_bar')){
            $this->fail('Backreference match did not work');
        }

    }

    public function testComplex()
    {

        $regex = RegexBuilder::create()
            ->start()
            ->string('!')
            ->letter()->repeated()->captureAs('firstWord')
            ->string('_')
            ->number()->repeated()->captureAs('id')
            ->string('_')
            ->matchCaptured('firstWord')
            ->string('_')
            ->alphanumeric()->repeated()
            ->anything()
            ->pattern('[GATC]')->repeated(1,7)->captureAs('dna')
            ->string('!')
            ->end()
            ->getRegex();

        $string = '!bar_1092834_bar_1E2Ghs34/GATTACA!';

        $this->assertRegExp($regex, $string);

        preg_match($regex, $string, $match);

        $this->assertArrayHasKey('firstWord', $match);
        $this->assertEquals('bar', $match['firstWord']);

        $this->assertArrayHasKey('id', $match);
        $this->assertEquals('1092834', $match['id']);

        $this->assertArrayHasKey('dna', $match);
        $this->assertEquals('GATTACA', $match['dna']);

    }

}