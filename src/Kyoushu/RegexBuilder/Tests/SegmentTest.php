<?php

namespace Kyoushu\RegexBuilder\Tests;

use Kyoushu\RegexBuilder\RegexBuilder;
use Kyoushu\RegexBuilder\Segment;

class SegmentTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var RegexBuilder
     */
    protected $builder;

    protected function setUp()
    {
        $this->builder = new RegexBuilder();
    }

    public function testString()
    {
        $segment = Segment::create($this->builder)->setString('foo)bar');
        $this->assertEquals('(?:foo\\)bar)', $segment->__toString());
    }

    public function testPattern()
    {
        $segment = Segment::create($this->builder)->setPattern('[a-zA-Z0-9]');
        $this->assertEquals('(?:[a-zA-Z0-9])', $segment->__toString());
    }

    public function testOptional()
    {
        $segment = Segment::create($this->builder)->setString('foobar')->setOptional(true);
        $this->assertEquals('(?:foobar)?', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foobar')->setSubstring(true)->setOptional(true);
        $this->assertEquals('((?:foobar))?', $segment->__toString());
    }

    public function testSubstring()
    {
        $segment = Segment::create($this->builder)->setString('foobar')->setSubstring(true);
        $this->assertEquals('((?:foobar))', $segment->__toString());
    }

    public function testSubstringName()
    {
        $segment = Segment::create($this->builder)->setString('foo')->setSubstringName('bar');
        $this->assertEquals('(?<bar>(?:foo))', $segment->__toString());
    }

    public function testRepeated()
    {
        $segment = Segment::create($this->builder)->setString('foo')->setRepeated(true);

        $this->assertEquals('(?:foo)+', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeated(1,2);
        $this->assertEquals('(?:foo){1,2}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeated(null,2);
        $this->assertEquals('(?:foo){,2}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeated(1,null);
        $this->assertEquals('(?:foo){1,}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeated(2,2);
        $this->assertEquals('(?:foo){2}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeatedMin(1);
        $this->assertEquals('(?:foo){1,}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeatedMax(2);
        $this->assertEquals('(?:foo){,2}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeatedMin(1)->setRepeatedMax(2);
        $this->assertEquals('(?:foo){1,2}', $segment->__toString());

        $segment = Segment::create($this->builder)->setString('foo')->setRepeatedMin(1)->setRepeatedMax(1);
        $this->assertEquals('(?:foo){1}', $segment->__toString());
    }

}