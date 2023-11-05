# fratily/clone-with

[English README.md](README.en.md)

`fratily/clone-with`はPHPのオブジェクトクローンを拡張する関数を追加するライブラリです。

オブジェクトをクローンするときに任意のプロパティの値を書き換えられるようにします。
これは書き換えるプロパティがreadonlyでもprivateでも機能します。

NOTE: 現時点では継承しているスーパークラスのprivateプロパティを書き換えることはできません。
これは「スーパークラスのプライベートプロパティは隠ぺいされているし、外部から触られるべきではないんじゃね？」という作者の考えに基づいています。

## 使い方

このライブラリは、readonlyなプロパティで構成される不変オブジェクトをより使いやすくするために作成されました。

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
