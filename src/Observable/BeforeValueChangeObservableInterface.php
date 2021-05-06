<?php

namespace Reliese\Patterns\Observable;

/**
 * Interface BeforeValueChangeObservableInterface
 */
interface BeforeValueChangeObservableInterface
{
    /**
     * @param callable    $observerFunction
     * @param array       $valueKeyFilters
     * @param string|null $observerKey An optional string that can be used to remove the observer
     *
     * @return string The Observer Key was passed as $observerKey or the generated value that was used
     */
    public function addBeforeValueChangeObserver(
        callable $observerFunction,
        array $valueKeyFilters = [],
        ?string $observerKey = null,
    ): string;

    /**
     * This function allows a method to stop observing changes by specifying the key that was returned when it began
     * observing changes
     *
     * @param string $observerKey
     */
    public function removeBeforeValueChangeObserver(string $observerKey): void;

}
