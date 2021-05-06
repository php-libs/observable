
# PhpLibs\Observable

This package contains a simple set of classes to facilitate adding observers to property methods.

# Features

- Execute `callable` before any value changes

- Execute `callable` before specific values change

- Execute `callable` after any value changes

- Execute `callable` after specific values change

# Quick Start

## Install

`composer require php-libs/observable`

## Example Class Without Observable Properties

```php
class MyClass
{
    private ?string $propertyA = null;

    private ?string $propertyB = null;

    public function setPropertyA(string $value)
    {
        $this->propertyA = $value;
    }

    public function setPropertyB(string $value)
    {
        $this->propertyB = $value;
    }
}
```

## Example Class With Observable Properties

```php

class MyClass implements \PhpLibs\Observable\BeforeValueChangeObservableInterface
{
    private const PROPERTY_A = 'propertyA';
    private const PROPERTY_B = 'propertyB';
    
    use PhpLibs\Observable\BeforeValueChangeObservableTrait;
    use PhpLibs\Observable\AfterValueChangeObservableTrait;

    private ?string $propertyA = null;

    private ?string $propertyB = null;

    public function setPropertyA(string $value)
    {
        $this->raiseBeforeValueChange(static::PROPERTY_A, $this->propertyA, $value);
        $this->propertyA = $value;
        $this->raiseAfterValueChange(static::PROPERTY_A, $this->propertyA);
    }

    public function setPropertyB(string $value)
    {
        $this->raiseBeforeValueChange(static::PROPERTY_B, $this->propertyB, $value);
        $this->propertyB = $value;
        $this->raiseAfterValueChange(static::PROPERTY_B, $this->propertyB);
    }
}
```

## Full Example

Please see [example.php](examples/example.php)

Which outputs as follows:
```
propertyA WILL CHANGE from "" to "A-1"
propertyB WILL CHANGE from "" to "B-1"
Begin observing After B Changed
propertyA WILL CHANGE from "A-1" to "A-2"
propertyB WILL CHANGE from "B-1" to "B-2"
propertyB CHANGED to "B-2"
End observing After B Changed
propertyA WILL CHANGE from "A-2" to "A-3"
propertyB WILL CHANGE from "B-2" to "B-3"
```