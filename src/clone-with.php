<?php

namespace Fratily\CloneWith;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Returns a clone of the specified object.
 *
 * Using $new_props, property values can be rewritten when cloning.
 * May be used to rewrite readonly property values when cloning.
 *
 * @param object $source_object Objects to be cloned.
 * @param mixed[] $new_props Map of names and values of properties to be rewritten.
 *
 * @phpstan-template T of object
 * @phpstan-param T $source_object
 * @phpstan-param array<non-empty-string, mixed> $new_props
 * @phpstan-return T
 */
function clone_with_new_props(object $source_object, array $new_props): object
{
    $leaf_class_reflection = new ReflectionClass($source_object);

    if (!$leaf_class_reflection->isCloneable()) {
        throw new InvalidArgumentException($leaf_class_reflection->getName() . ' cannot be cloned.');
    }

    /** Object to hold the result of processing in `__clone`. */
    $clone_object = clone $source_object;
    /** Objects to return. */
    $make_object = $leaf_class_reflection->newInstanceWithoutConstructor();

    $is_leaf_class = true;
    $class_reflection = $leaf_class_reflection;
    do {
        foreach ($class_reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            if (!$is_leaf_class) {
                if (!$property->isPrivate()) {
                    // Non-private properties of the superclass are processed as properties of the leaf class.
                    continue;
                }

                // Private properties of the superclass should remain at their original values.
                // At least `cloneWith` does not support such processing.
                $property->setValue($make_object, $property->getValue($clone_object));
                continue;
            }

            $value = array_key_exists($property->getName(), $new_props)
                ? $new_props[$property->getName()]
                : $property->getValue($clone_object);
            unset($new_props[$property->getName()]);
            // If trying to set value does not match the type of the property, a Fatal Error will occur.
            $property->setValue($make_object, $value);
        }

        $is_leaf_class = false;
    } while ($class_reflection = $class_reflection->getParentClass());

    return $make_object;
}
