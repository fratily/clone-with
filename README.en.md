# fratily/clone-with

`fratily/clone-with` is a library that adds functions to extend PHP's clone object.

Provides the ability to rewrite the value of any property when cloning an object.
This ability works whether the property is readonly or private.

NOTE: At this time, do not provide the ability to rewrite private properties of inherited superclasses.
This is based on the author's idea, "private properties of a superclass should be hidden and not touched from the outside, right?"

## How to use

This library was created to make immutable objects composed of readonly properties easier to use.

```php
class Foo {
  public function __construct(
    public readonly string $value_string,
    public readonly int $value_int,
  ) {}

  public function withString(string $new_value)
  {
    return clone_with_new_props($this, ['value_string' => $new_value]);
  }

  public function withInt(int $new_value)
  {
    return clone_with_new_props($this, ['value_int' => $new_value]);
  }
}
```
