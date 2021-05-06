<?php
include __DIR__."/../vendor/autoload.php";

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

$onBeforeValueChange = function (string $valueKey, mixed $currentValue, mixed $newValue): void {
    echo "$valueKey WILL CHANGE from \"$currentValue\" to \"$newValue\"\n";
};

$onAfterValueBChange = function (string $valueKey, mixed $currentValue): void {
    echo "$valueKey CHANGED to \"$currentValue\"\n";
};

$myObj = new MyClass();
$myObj->addBeforeValueChangeObserver($onBeforeValueChange);

$myObj->setPropertyA("A-1");
$myObj->setPropertyB("B-1");

// Begin observing After B Changed
echo "Begin observing After B Changed\n";
$propBObserverKey = $myObj->addAfterValueChangeObserver($onAfterValueBChange, ['propertyB'], 'Prop-B-Observer');

$myObj->setPropertyA("A-2");
$myObj->setPropertyB("B-2");

// End observing After B Changed
echo "End observing After B Changed\n";
$myObj->removeAfterValueChangeObserver($propBObserverKey);
$myObj->setPropertyA("A-3");
$myObj->setPropertyB("B-3");
