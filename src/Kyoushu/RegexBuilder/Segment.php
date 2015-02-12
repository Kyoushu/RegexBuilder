<?php

namespace Kyoushu\RegexBuilder;

class Segment {

    /**
     * @var bool
     */
    protected $substring;

    /**
     * @var string
     */
    protected $substringName;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $string;

    /**
     * @var RegexBuilder
     */
    protected $builder;

    /**
     * @var int|bool|null
     */
    protected $repeatedMin;

    /**
     * @var int|bool|null
     */
    protected $repeatedMax;

    /**
     * @var bool
     */
    protected $optional;

    /**
     * @return Segment
     */
    static function create()
    {
        return new self();
    }

    /**
     * @return bool|int|null
     */
    public function getRepeatedMin()
    {
        return $this->repeatedMin;
    }

    /**
     * @param bool|int|null $repeatedMin
     * @return $this
     */
    public function setRepeatedMin($repeatedMin)
    {
        $this->repeatedMin = $repeatedMin;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getRepeatedMax()
    {
        return $this->repeatedMax;
    }

    /**
     * @param bool|int|null $repeatedMax
     * @return $this
     */
    public function setRepeatedMax($repeatedMax)
    {
        $this->repeatedMax = $repeatedMax;
        return $this;
    }

    public function setRepeated($min = null, $max = null)
    {
        $this->repeatedMin = $min;
        $this->repeatedMax = $max;
        return $this;
    }

    /**
     * @return RegexBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @param RegexBuilder $builder
     * @return $this
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
        return $this;
    }



    /**
     * @return boolean
     */
    public function isSubstring()
    {
        return $this->substring;
    }

    /**
     * @param boolean $substring
     * @return $this
     */
    public function setSubstring($substring)
    {
        $this->substring = $substring;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubstringName()
    {
        return $this->substringName;
    }

    /**
     * @param string $substringName
     * @return $this
     */
    public function setSubstringName($substringName)
    {
        $this->setSubstring(true);
        $this->substringName = $substringName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * @param boolean $optional
     * @return $this
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;
        return $this;
    }

    public function __toString()
    {
        $regex = '';
        if($this->substring){
            $regex .= '(';
            if($this->substringName){
                $regex .= sprintf('?<%s>', $this->substringName);
            }
        }

        $regex .= '(?:';
        if($this->string){
            $regex .= preg_quote($this->string, $this->builder->getDelimimiter());
        }
        else{
            $regex .= $this->pattern;
        }
        $regex .= ')';
        if($this->optional && !$this->substring) $regex .= '?';

        if($this->repeatedMin || $this->repeatedMax)
        {
            if($this->repeatedMin === true){
                $regex .= '+';
            }
            elseif(is_int($this->repeatedMin) && !is_int($this->repeatedMax)){
                $regex .= sprintf('{%s,}', $this->repeatedMin);
            }
            elseif(!is_int($this->repeatedMin) && is_int($this->repeatedMax)){
                $regex .= sprintf('{,%s}', $this->repeatedMax);
            }
            elseif(is_int($this->repeatedMin) && is_int($this->repeatedMax)){
                if($this->repeatedMin === $this->repeatedMax){
                    $regex .= sprintf('{%s}', $this->repeatedMin);
                }
                else{
                    $regex .= sprintf('{%s,%s}', $this->repeatedMin, $this->repeatedMax);
                }
            }
        }

        if($this->substring){
            $regex .= ')';
            if($this->optional) $regex .= '?';
        }
        return $regex;
    }

}