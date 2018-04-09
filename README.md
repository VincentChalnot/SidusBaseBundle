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
