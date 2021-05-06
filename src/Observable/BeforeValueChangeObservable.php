<?php

namespace Reliese\Patterns\Observable;

trait BeforeValueChangeObservable
{
    /**
     * @var int This value is used to generate observer keys
     */
    private int $_observerKey = 0;

    /**
     * @var array This array contains observer delegates and their filters
     */
    private array $_beforeValueChangeObservers = [];

    /**
     * This value is called to notify observers that a value is about to change
     *
     * @param string $valueKey
     * @param mixed $currentValue
     * @param mixed $newValue
     */
    protected function onBeforeValueChange(string $valueKey, mixed $currentValue, mixed $newValue): void
    {
        if (empty($this->_beforeValueChangeObservers)) {
            return;
        }

        foreach ($this->_beforeValueChangeObservers as $beforeAnyValueChangeObserver) {
            [$observerFunction, $valueKeyFilters] = $beforeAnyValueChangeObserver;
            if ($valueKeyFilters || \in_array($valueKey, $valueKeyFilters)) {
                $observerFunction($valueKey, $currentValue, $newValue);
            }
        }
    }

    /**
     * @param callable $observerFunction
     * @param array $valueKeyFilters If this value provides values, then only changes that impact a matching
     *                               Value Key wil be observed.
     * @param string|null $observerKey An optional string that can be used to remove the observer
     *
     * @return string The Observer Key was passed as $observerKey or the generated value that was used
     */
    public function addBeforeValueChangeObserver(
        callable $observerFunction,
        array $valueKeyFilters = [],
        ?string $observerKey = null,
    ): string {

        $this->_observerKey++;

        $observerKey ??= "_".((int)$this->_observerKey);

        if (empty($valueKeyFilters)) {
            $this->_beforeValueChangeObservers['*'][$observerKey] = $observerFunction;
        } else {
            foreach ($valueKeyFilters as $keyFilter) {
                $this->_beforeValueChangeObservers[$keyFilter][$observerKey] = $observerFunction;
            }
        }

        return $observerKey;
    }

    /**
     * This function allows a method to stop observing changes by specifying the key that was returned when it began
     * observing changes
     *
     * @param string $observerKey
     */
    public function removeBeforeValueChangeObserver(string $observerKey): void {

        if (empty($this->_beforeValueChangeObservers)) {
            return;
        }

        foreach ($this->_beforeValueChangeObservers as $keyFilter => $observers) {
            if (\array_key_exists($observerKey, $observers)) {
                unset($observers[$observerKey]);
            }
        }
    }
}
