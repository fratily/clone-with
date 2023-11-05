<?php

namespace Fratily\Tests\CloneWith\Unit;

use Fratily\Tests\CloneWith\Mock\ExtendNotCloneable;
use Fratily\Tests\CloneWith\Mock\HasPrivateProp;
use Fratily\Tests\CloneWith\Mock\HasProtectedProp;
use Fratily\Tests\CloneWith\Mock\HasPublicProp;
use Fratily\Tests\CloneWith\Mock\HasReadonlyPrivateProp;
use Fratily\Tests\CloneWith\Mock\HasReadonlyProtectedProp;
use Fratily\Tests\CloneWith\Mock\HasReadonlyPublicProp;
use Fratily\Tests\CloneWith\Mock\NotCloneable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function Fratily\CloneWith\clone_with_new_props;

class CloneWithNewPropsTest extends TestCase
{
    /**
     * @dataProvider dataProvider_errorNotCloneableClass
     *
     * @param class-string $not_cloneable_class_name
     */
    public function test_errorNotCloneableObject(string $not_cloneable_class_name): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($not_cloneable_class_name . ' cannot be cloned.');

        $obj = new $not_cloneable_class_name();

        clone_with_new_props($obj, []);
    }

    /**
     * @return array<non-empty-string, array{class-string}>
     */
    public static function dataProvider_errorNotCloneableClass(): array
    {
        return [
            'not cloneable class' => [NotCloneable::class],
        ];
    }


    /**
     * @dataProvider dataProvider_clone
     *
     * @param array<non-empty-string, mixed> $new_props
     * @param array<class-string, array<non-empty-string, mixed>> $expected_props
     */
    public function test_clone(object $source_object, array $new_props, array $expected_props): void
    {
        $actual_object = clone_with_new_props($source_object, $new_props);

        self::assertSame(get_class($source_object), get_class($actual_object));

        foreach ($expected_props as $class_name => $props) {
            $class_ref = new ReflectionClass($class_name);
            foreach ($props as $prop_name => $prop_value) {
                self::assertSame($prop_value, $class_ref->getProperty($prop_name)->getValue($actual_object));
            }
        }
    }

    /**
     * @return array<non-empty-string, array{
     *   object,
     *   array<non-empty-string, mixed>,
     *   array<class-string, array<non-empty-string, mixed>>
     * }>
     */
    public static function dataProvider_clone(): array
    {
        return [
            // original value
            'clone and copy private prop' => [
                new HasPrivateProp(),
                [],
                [HasPrivateProp::class =>  ['private' => 'private property']],
            ],
            'clone and copy protected prop' => [
                new HasProtectedProp(),
                [],
                [HasProtectedProp::class => ['protected' => 'protected property']],
            ],
            'clone and copy public prop' => [
                new HasPublicProp(),
                [],
                [HasPublicProp::class => ['public' => 'public property']],
            ],
            'clone and copy readonly private prop' => [
                new HasReadonlyPrivateProp(),
                [],
                [HasReadonlyPrivateProp::class => ['private' => 'private readonly property']],
            ],
            'clone and copy readonly protected prop' => [
                new HasReadonlyProtectedProp(),
                [],
                [HasReadonlyProtectedProp::class => ['protected' => 'protected readonly property']],
            ],
            'clone and copy readonly public prop' => [
                new HasReadonlyPublicProp(),
                [],
                [HasReadonlyPublicProp::class => ['public' => 'public readonly property']],
            ],
            // edit value
            'clone and edit private prop' => [
                new HasPrivateProp(),
                ['private' => 'new value'],
                [HasPrivateProp::class =>  ['private' => 'new value']],
            ],
            'clone and edit protected prop' => [
                new HasProtectedProp(),
                ['protected' => 'new value'],
                [HasProtectedProp::class => ['protected' => 'new value']],
            ],
            'clone and edit public prop' => [
                new HasPublicProp(),
                ['public' => 'new value'],
                [HasPublicProp::class => ['public' => 'new value']],
            ],
            'clone and edit readonly private prop' => [
                new HasReadonlyPrivateProp(),
                ['private' => 'new value'],
                [HasReadonlyPrivateProp::class => ['private' => 'new value']],
            ],
            'clone and edit readonly protected prop' => [
                new HasReadonlyProtectedProp(),
                ['protected' => 'new value'],
                [HasReadonlyProtectedProp::class => ['protected' => 'new value']],
            ],
            'clone and edit readonly public prop' => [
                new HasReadonlyPublicProp(),
                ['public' => 'new value'],
                [HasReadonlyPublicProp::class => ['public' => 'new value']],
            ],
            // todo add test case
        ];
    }
}
