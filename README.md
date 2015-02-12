# Kyoushu\RegexBuilder

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