<?php

namespace PhpLibs\Observable;

trait AfterValueChangeObservableTrait
{
    /**
     * @var int This value is used to generate observer keys
     */
    private int $_observerKeys = 0;

    /**
     * @var array This array contains observer delegates and their filters
     */
    private array $_afterValueChangeObservers = [];

    /**
     * This value is called to notify observers that a value is about to change
     *
     * @param string $valueKey
     * @param mixed $newValue
     */
    protected function raiseAfterValueChange(string $valueKey, mixed $currentValue): void
    {
        Observable::raiseAfterValueChange(
            $valueKey,
            $currentValue,
            $this->_afterValueChangeObservers
        );
    }

    /**
     * @param callable $observerFunction
     * @param array $valueKeyFilters If this value provides values, then only changes that impact a matching
     *                               Value Key wil be observed. Defaults to [Observable::ALL_VALUES_KEYS]
     * @param string|null $observerKey An optional string that can be used to remove the observer
     *
     * @return string The Observer Key was passed as $observerKey or the generated value that was used
     */
    public function addAfterValueChangeObserver(
        callable $observerFunction,
        array $valueKeyFilters = [Observable::ALL_VALUES_KEYS],
        ?string $observerKey = null,
    ): string {
        return Observable::addObserver(
            $observerFunction,
            $valueKeyFilters,
            $observerKey,
            $this->_observerKeys,
            $this->_afterValueChangeObservers
        );
    }

    /**
     * This function allows a method to stop observing changes by specifying the key that was returned when it began
     * observing changes
     *
     * @param string $observerKey
     */
    public function removeAfterValueChangeObserver(string $observerKey): void
    {
        Observable::removeObserver($observerKey, $this->_afterValueChangeObservers);
    }
}
