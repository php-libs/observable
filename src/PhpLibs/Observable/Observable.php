<?php

namespace PhpLibs\Observable;

use const PHP_EOL;
/**
 * Class Observable
 */
abstract class Observable
{
    const ALL_VALUES_KEYS = '*';

    private function __construct()
    {
    }

    public static function addObserver(
        callable $observerFunction,
        array $valueKeyFilters,
        ?string $observerKey,
        int &$_observerKeysAdded,
        array &$_valueChangeObservers
    ): string {
        $_observerKeysAdded += 1;
        $observerKey ??= "_".((int)$_observerKeysAdded);

        /*
         * Assign the observer to each specified key
         */
        foreach ($valueKeyFilters as $keyFilter) {
            $_valueChangeObservers[$keyFilter][$observerKey] = $observerFunction;
        }

        return $observerKey;
    }

    public static function removeObserver(string $observerKey, array &$_valueChangeObservers)
    {
        if (empty($_valueChangeObservers)) {
            return;
        }

        foreach ($_valueChangeObservers as $keyFilter => $observers) {
            if (\array_key_exists($observerKey, $observers)) {
                unset($_valueChangeObservers[$keyFilter][$observerKey]);
            }
        }
    }
    
    public static function raiseAfterValueChange(
        object $source,
        string $valueKey,
        mixed $currentValue,
        array &$_afterValueChangeObservers
    ) : void {
        if (empty($_afterValueChangeObservers)) {
            return;
        }

        /*
         * Notify observers for the specific value key
         */
        if (\array_key_exists($valueKey, $_afterValueChangeObservers)) {
            foreach ($_afterValueChangeObservers[$valueKey] as $observer) {
                $observer($source, $valueKey, $currentValue);
            }
        }

        /*
         * Notify observers of all value keys
         */
        if (\array_key_exists('*', $_afterValueChangeObservers)) {
            foreach ($_afterValueChangeObservers[static::ALL_VALUES_KEYS] as $observer) {
                $observer($source, $valueKey, $currentValue);
            }
        }
    }
    
    public static function raiseBeforeValueChange(
        object $source,
        string $valueKey, 
        mixed $currentValue,
        mixed $newValue,
        array &$_beforeValueChangeObservers
    ) : void {
        if (empty($_beforeValueChangeObservers)) {
            return;
        }

        /*
         * Notify observers for the specific value key
         */
        if (\array_key_exists($valueKey, $_beforeValueChangeObservers)) {
            foreach ($_beforeValueChangeObservers[$valueKey] as $observer) {
                $observer($source, $valueKey, $currentValue, $newValue);
            }
        }

        /*
         * Notify observers of all value keys
         */
        if (\array_key_exists('*', $_beforeValueChangeObservers)) {
            foreach ($_beforeValueChangeObservers[static::ALL_VALUES_KEYS] as $observer) {
                $observer($source, $valueKey, $currentValue, $newValue);
            }
        }        
    }
}