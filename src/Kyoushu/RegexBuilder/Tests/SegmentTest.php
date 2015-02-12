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
        $segment = Segment::create()->setString('foo)bar');
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo\\)bar)', $segment->__toString());
    }

    public function testPattern()
    {
        $segment = Segment::create()->setPattern('[a-zA-Z0-9]');
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:[a-zA-Z0-9])', $segment->__toString());
    }

    public function testOptional()
    {
        $segment = Segment::create()->setString('foobar')->setOptional(true);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foobar)?', $segment->__toString());

        $segment = Segment::create()->setString('foobar')->setSubstring(true)->setOptional(true);
        $segment->setBuilder($this->builder);
        $this->assertEquals('((?:foobar))?', $segment->__toString());
    }

    public function testSubstring()
    {
        $segment = Segment::create()->setString('foobar')->setSubstring(true);
        $segment->setBuilder($this->builder);
        $this->assertEquals('((?:foobar))', $segment->__toString());
    }

    public function testSubstringName()
    {
        $segment = Segment::create()->setString('foo')->setSubstringName('bar');
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?<bar>(?:foo))', $segment->__toString());
    }

    public function testRepeated()
    {
        $segment = Segment::create()->setString('foo')->setRepeated(true);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo)+', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeated(1,2);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){1,2}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeated(null,2);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){,2}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeated(1,null);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){1,}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeated(2,2);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){2}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeatedMin(1);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){1,}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeatedMax(2);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){,2}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeatedMin(1)->setRepeatedMax(2);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){1,2}', $segment->__toString());

        $segment = Segment::create()->setString('foo')->setRepeatedMin(1)->setRepeatedMax(1);
        $segment->setBuilder($this->builder);
        $this->assertEquals('(?:foo){1}', $segment->__toString());
    }

}