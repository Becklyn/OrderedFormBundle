Ordered Form Bundle
===================

A bundle that makes form fields sortable.


Installation
------------

```bash
composer require becklyn/ordered-form-bundle
```


Usage
-----

This bundle adds a new form option called `position`:

```php
class SomeForm extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm (FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add("field", null, [
                "position" => /* some value */
            ]);
    }
}
```

The supported values are:

| Value | Description |
| ----- | ----------- |
| `"position" => "first"` | Places the element as the first element in the form. |
| `"position" => "last"` | Places the element as the last element in the form. |
| `"position" => 42` | A simple sort order (the lower the number, the more at the top it is). Works with any integer. |
| `"position" => ["before" => "otherfield"]` | Places the field before another one. |
| `"position" => ["after" => "otherfield"]` | Places the field after another one. |


Caveats
-------

This bundle focuses on speed, so the sorting is not perfect. As it is pretty easy to create a conflicting, this bundle
tries a best-effort sorting, but this implies:

*   A `first` field isn't guaranteed to be the first one (eg. if there are multiple `first`).
*   `before` and `after` only guarantee the relative order, not how big the distance is between these fields (it tries
    to place them immediately next to each other).
    
If the configuration of the form is sensible and conflict-free, then the order will work as expected.
