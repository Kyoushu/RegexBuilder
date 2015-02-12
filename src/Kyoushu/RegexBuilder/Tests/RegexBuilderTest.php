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
        $this->assertEquals('/(?:[a-zA-Z])/', $regex);
    }

    public function testString()
    {
        $regex = RegexBuilder::create()->string('(foo)bar')->getRegex();
        $this->assertEquals('/(?:\\(foo\\)bar)/', $regex);
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
        $this->assertEquals('/(?:foo)?/', $regex);
    }

    public function testLetter()
    {
        $regex = RegexBuilder::create()->letter()->getRegex();
        $this->assertEquals('/(?:[a-zA-Z])/', $regex);
    }

    public function testNumber()
    {
        $regex = RegexBuilder::create()->number()->getRegex();
        $this->assertEquals('/(?:[0-9])/', $regex);
    }

    public function testAnything()
    {
        $regex = RegexBuilder::create()->anything()->getRegex();
        $this->assertEquals('/(?:.)/', $regex);
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
            ->letter()->repeated()->captureAs('firstWord')
            ->string('_')
            ->matchCaptured('firstWord')
            ->string('_')
            ->alphanumeric()->repeated()
            ->getRegex();

        $this->assertRegExp($regex, 'bar_bar_1E2Ghs34');

    }

}