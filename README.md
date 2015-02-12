# Kyoushu\RegexBuilder

[![Build Status](https://travis-ci.org/Kyoushu/RegexBuilder.svg?branch=master)](https://travis-ci.org/Kyoushu/RegexBuilder)  [![Coverage Status](https://coveralls.io/repos/Kyoushu/RegexBuilder/badge.svg)](https://coveralls.io/r/Kyoushu/RegexBuilder)

A library for building simple regex strings procedurally

## Example

    $regex = RegexBuilder::create()
        ->start()
        ->letter()->repeated()->captureAs('office')
        ->string('_')
        ->number()->repeated(3)->captureAs('id')
        ->end()
        ->getRegex();
        
    preg_match($regex, 'OfficeName_123', $match); // Returns TRUE
    
    $regex = RegexBuilder::create()
        ->start()
        ->letter()->repeated()->captureAs('firstWord')
        ->string('_')
        ->matchCaptured('firstWord')
        ->string('_')
        ->number()->repeated()
        ->end()
        ->getRegex();
        
    preg_match($regex, 'bar_bar_0293', $match); // Returns TRUE