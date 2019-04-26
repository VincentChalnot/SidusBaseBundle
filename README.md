Sidus/BaseBundle Documentation
==================================

## Service loading

If you simply inherit from the SidusBaseExtension, all the YAML files present in this directory will be loaded:
````./Resources/config/services````

````php
<?php

namespace FooBarBundle\DependencyInjection;

use Sidus\BaseBundle\DependencyInjection\SidusBaseExtension;

class FooBarExtension extends SidusBaseExtension
{
}
````

## Routing

Instead of using controllers, declare service for each action and then you just have to declare the routing like this:
````yaml
FooBarBundle\Action\MyAction: # Route name must match service id
    path: /path/{parameter}
````

You don't need to declare the ````_controller```` part, it will be loaded with your route name.

## Param converter

Take a look at the ````AbstractParamConverter````, declaring param converter is much more simple now

## Compiler passes

The ````GenericCompilerPass```` allows you to easily inject all services tagged with a specific tag into another
service.

## Utilities

### DateTime parsing

````DateTimeUtility::parse```` will allow you to parse date time from multiple formats easily.

### Translation

Use the ````TranslatorUtility```` to iterate over various translation keys in order to return the first matching one.

## Validator

You can use the ````BaseLoader```` class to load a sets of constraints from a PHP array with the same syntax than Yaml:

````php
<?php
/** @var \Symfony\Component\Validator\Validator\ValidatorInterface $validator */

use Sidus\BaseBundle\Validator\Mapping\Loader\BaseLoader;

$loader = new BaseLoader();

$constraints = $loader->loadCustomConstraints([
    ['Regex' => ['pattern' => '/^[a-z0-9]+(?:[-\_][a-z0-9]+)*$/']],
    ['NotNull' => null],
]);

foreach ($constraints as $constraint) {
    $violations = $validator->validate($data, $constraint);
    // Do stuff with the violations
}
````

## Forms

The ````ChoiceTypeExtension```` allows choice form types to work with iterable objects. You don't need to do anything.

A new option is available for any form type: ```block_prefix``` allows you to directly choose a custom block prefix for
form rendering.

## Serializer

The NestedPropertyDenormalizer can be very useful for denormalizing API responses into proper model entities, it works
without any setters and can denormalize embed data and embed entity collections based on very simple annotations.

### Installation

You need to manually require the symfony/serializer package in order for this to work, then add this definition in your
services definitions:

```yaml
services:
    Sidus\BaseBundle\Serializer\Normalizer\NestedPropertyDenormalizer:
        public: false
        arguments:
            - '@serializer.mapping.class_metadata_factory'
            - '@serializer.name_converter.camel_case_to_snake_case' # Configure this according to your needs
            - '@?property_info'
        calls:
            - [setAnnotationReader, ['@annotations.reader']]
        tags:
            - { name: serializer.normalizer, priority: -999 } # Change the priority if needed
```

This denormalizer will only work on PHP classes with the ```@NestedPropertyDenormalizer``` annotation.

### Configuration

Configuration example

```php
<?php

namespace FooBar\Model;

use Sidus\BaseBundle\Serializer\Annotation\NestedClass;
use Sidus\BaseBundle\Serializer\Annotation\NestedPropertyDenormalizer;

/**
 * @NestedPropertyDenormalizer()
 */
class Book
{
    /** @var string */
    protected $id;
    
    /**
     * @var \DateTimeInterface|null
     *
     * @NestedClass(targetClass="DateTime")
     */
    protected $publicationDate;
    
    /**
     * @var Author|null
     *
     * @NestedClass(targetClass="FooBar\Model\Author")
     */
    protected $author;

    /**
     * @var Edition[]
     *
     * @NestedClass(targetClass="FooBar\Model\Edition", multiple=true)
     */
    protected $editions = [];
    
    // Here be getters (no setters needed)
}
```

Note that the ```@NestedClass``` annotation can target any class.
