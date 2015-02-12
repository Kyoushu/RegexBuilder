<?php

namespace Kyoushu\RegexBuilder;

class RegexBuilder {

    const DEFAULT_DELIMITER = '/';

    /**
     * @var string
     */
    protected $delimimiter;

    /**
     * @var Segment
     */
    protected $lastSegment;

    /**
     * @var Segment[]
     */
    protected $segments;

    /**
     * @var bool
     */
    protected $matchStringStart;

    /**
     * @var bool
     */
    protected $matchStringEnd;

    /**
     * @param null|string $delimimiter
     * @return RegexBuilder
     */
    static function create($delimimiter = null)
    {
        return new self($delimimiter);
    }

    /**
     * @param null|string $delimimiter
     */
    public function __construct($delimimiter = null)
    {
        if($delimimiter === null){
            $this->setDelimimiter(self::DEFAULT_DELIMITER);
        }
        else{
            $this->setDelimimiter($delimimiter);
        }

        $this->segments = array();
    }

    /**
     * @return $this
     */
    public function entireString()
    {
        $this->matchStringStart = true;
        $this->matchStringEnd = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function start()
    {
        $this->matchStringStart = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function end()
    {
        $this->matchStringEnd = true;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDelimimiter()
    {
        return $this->delimimiter;
    }

    /**
     * @param mixed $delimimiter
     * @return $this
     */
    public function setDelimimiter($delimimiter)
    {
        $this->delimimiter = $delimimiter;
        return $this;
    }

    /**
     * @param Segment $segment
     * @return $this
     */
    public function addSegment(Segment $segment)
    {
        $this->segments[] = $segment;
        $this->lastSegment = $segment;
        return $this;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function pattern($pattern)
    {
        $this->addSegment(
            Segment::create()->setPattern($pattern)
        );
        return $this;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function string($string)
    {
        $this->addSegment(
            Segment::create()->setString($string)
        );
        return $this;
    }

    public function alphanumeric()
    {
        $this->addSegment(
            Segment::create()->setPattern('[a-zA-Z0-9]')
        );
        return $this;
    }

    /**
     * @param null|string $name
     * @return $this
     */
    public function captureAs($name = null)
    {
        $this->lastSegment->setSubstring(true);
        if($name !== null) $this->lastSegment->setSubstringName($name);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function matchCaptured($name)
    {
        $this->addSegment(
            Segment::create()
                ->setPattern(sprintf('(?P=%s)', $name))
        );
        return $this;
    }

    /**
     * @param bool|null|int $min
     * @param bool|null|int $max
     * @return $this
     */
    public function repeated($min = true, $max = null)
    {
        if($max === null) $max = $min;
        $this->lastSegment->setRepeated($min, $max);
        return $this;
    }

    public function optional()
    {
        $this->lastSegment->setOptional(true);
        return $this;
    }

    public function letter()
    {
        $this->addSegment(
            Segment::create()->setPattern('[a-zA-Z]')
        );
        return $this;
    }

    public function number()
    {
        $this->addSegment(
            Segment::create()->setPattern('[0-9]')
        );
        return $this;
    }

    public function anything()
    {
        $this->addSegment(
            Segment::create()->setPattern('.')
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $regex = $this->delimimiter;
        if($this->matchStringStart) $regex .= '^';
        foreach($this->segments as $segment){
            $segment->setBuilder($this);
            $regex .= $segment->__toString();
        }
        if($this->matchStringEnd) $regex .= '$';
        $regex .= $this->delimimiter;
        $regex .= 'ms'; // Set internal options
        return $regex;
    }

}