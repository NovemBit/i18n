# NovemBit i18n - Internationalization framework library

## Table of Contents

* [Arrays](#arrays)
    * [arrayWalkWithRoute](#arraywalkwithroute)
    * [arrayMergeRecursiveDistinct](#arraymergerecursivedistinct)
* [DataMapper](#datamapper)
    * [getDB](#getdb)
* [DB](#db)
    * [__construct](#__construct)
    * [commonInit](#commoninit)
    * [isCli](#iscli)
    * [getLogger](#getlogger)
    * [setLogger](#setlogger)
    * [getRuntimeDir](#getruntimedir)
    * [setRuntimeDir](#setruntimedir)
    * [setCachePool](#setcachepool)
    * [getCachePool](#getcachepool)
    * [defaultConfig](#defaultconfig)
    * [getConnection](#getconnection)
    * [setConnection](#setconnection)
* [DB](#db-1)
    * [__construct](#__construct-1)
    * [commonInit](#commoninit-1)
    * [isCli](#iscli-1)
    * [getLogger](#getlogger-1)
    * [setLogger](#setlogger-1)
    * [getRuntimeDir](#getruntimedir-1)
    * [setRuntimeDir](#setruntimedir-1)
    * [setCachePool](#setcachepool-1)
    * [getCachePool](#getcachepool-1)
    * [defaultConfig](#defaultconfig-1)
    * [getConnection](#getconnection-1)
    * [getConnectionParams](#getconnectionparams)
* [Dummy](#dummy)
* [Exception](#exception)
* [Google](#google)
    * [mainInit](#maininit)
* [HTML](#html)
    * [get](#get)
    * [saveTranslations](#savetranslations)
    * [getDB](#getdb-1)
* [HTML](#html-1)
    * [defaultConfig](#defaultconfig-2)
    * [getParserType](#getparsertype)
    * [setParserType](#setparsertype)
    * [buildToTranslateFields](#buildtotranslatefields)
    * [replaceTranslatedFields](#replacetranslatedfields)
    * [getHelperAttributes](#gethelperattributes)
    * [setHelperAttributes](#sethelperattributes)
    * [addBeforeParseCallback](#addbeforeparsecallback)
    * [addAfterParseCallback](#addafterparsecallback)
    * [getBeforeParseCallbacks](#getbeforeparsecallbacks)
    * [getAfterParseCallbacks](#getafterparsecallbacks)
* [HTMLFragment](#htmlfragment)
    * [defaultConfig](#defaultconfig-3)
    * [getParserType](#getparsertype-1)
    * [setParserType](#setparsertype-1)
    * [buildToTranslateFields](#buildtotranslatefields-1)
    * [replaceTranslatedFields](#replacetranslatedfields-1)
    * [getHelperAttributes](#gethelperattributes-1)
    * [setHelperAttributes](#sethelperattributes-1)
    * [addBeforeParseCallback](#addbeforeparsecallback-1)
    * [addAfterParseCallback](#addafterparsecallback-1)
    * [getBeforeParseCallbacks](#getbeforeparsecallbacks-1)
    * [getAfterParseCallbacks](#getafterparsecallbacks-1)
* [JSON](#json)
    * [get](#get-1)
    * [saveTranslations](#savetranslations-1)
    * [getDB](#getdb-2)
* [LanguageException](#languageexception)
* [Method](#method)
    * [get](#get-2)
    * [saveTranslations](#savetranslations-2)
    * [getDB](#getdb-3)
* [MethodException](#methodexception)
* [Module](#module)
    * [__construct](#__construct-2)
    * [commonLateInit](#commonlateinit)
    * [mainInit](#maininit-1)
    * [isCli](#iscli-2)
    * [getLogger](#getlogger-2)
    * [setLogger](#setlogger-2)
    * [getRuntimeDir](#getruntimedir-2)
    * [setRuntimeDir](#setruntimedir-2)
    * [setCachePool](#setcachepool-2)
    * [getCachePool](#getcachepool-2)
    * [defaultConfig](#defaultconfig-4)
    * [start](#start)
    * [instance](#instance)
* [RequestException](#requestexception)
* [Rest](#rest)
    * [__construct](#__construct-3)
    * [isCli](#iscli-3)
    * [getLogger](#getlogger-3)
    * [setLogger](#setlogger-3)
    * [getRuntimeDir](#getruntimedir-3)
    * [setRuntimeDir](#setruntimedir-3)
    * [setCachePool](#setcachepool-3)
    * [getCachePool](#getcachepool-3)
    * [defaultConfig](#defaultconfig-5)
    * [start](#start-1)
    * [actionTranslate](#actiontranslate)
    * [actionIndex](#actionindex)
    * [actionRestrict](#actionrestrict)
* [Rest](#rest-1)
    * [getLanguagesConfig](#getlanguagesconfig)
* [Strings](#strings)
    * [getStringsDifference](#getstringsdifference)
* [Text](#text)
    * [get](#get-3)
    * [saveTranslations](#savetranslations-3)
    * [getDB](#getdb-4)
* [Translation](#translation)
    * [getDB](#getdb-5)
    * [get](#get-4)
    * [saveTranslations](#savetranslations-4)
* [TranslationException](#translationexception)
* [URL](#url)
    * [get](#get-5)
    * [saveTranslations](#savetranslations-5)
    * [getDB](#getdb-6)
    * [rules](#rules)
* [URL](#url-1)
    * [addQueryVars](#addqueryvars)
    * [removeQueryVars](#removequeryvars)
    * [buildUrl](#buildurl)
* [XML](#xml)
    * [defaultConfig](#defaultconfig-6)
    * [getParserType](#getparsertype-2)
    * [setParserType](#setparsertype-2)
    * [buildToTranslateFields](#buildtotranslatefields-2)
    * [replaceTranslatedFields](#replacetranslatedfields-2)
    * [getHelperAttributes](#gethelperattributes-2)
    * [setHelperAttributes](#sethelperattributes-2)
    * [addBeforeParseCallback](#addbeforeparsecallback-2)
    * [addAfterParseCallback](#addafterparsecallback-2)
    * [getBeforeParseCallbacks](#getbeforeparsecallbacks-2)
    * [getAfterParseCallbacks](#getafterparsecallbacks-2)
* [XML](#xml-1)
    * [get](#get-6)
    * [saveTranslations](#savetranslations-6)
    * [getDB](#getdb-7)

## Arrays





* Full name: \NovemBit\i18n\system\helpers\Arrays


### arrayWalkWithRoute

Recursive array walk with callback and route

```php
Arrays::arrayWalkWithRoute( array &$arr, callable $callback, \NovemBit\i18n\system\helpers\Strings $route = &#039;&#039;, \NovemBit\i18n\system\helpers\Strings $separator = &#039;&gt;&#039; ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$arr` | **array** | Main array |
| `$callback` | **callable** | Callback function with 3 params (key/val/route) |
| `$route` | **\NovemBit\i18n\system\helpers\Strings** | Parent route |
| `$separator` | **\NovemBit\i18n\system\helpers\Strings** | Route separator |




---

### arrayMergeRecursiveDistinct

array_merge_recursive does indeed merge arrays,
but it converts values with duplicate
keys to arrays rather than overwriting the value
in the first array with the duplicate
value in the second array,
as array_merge does. I.e., with array_merge_recursive,
this happens (documented behavior):

```php
Arrays::arrayMergeRecursiveDistinct( array &$array1, array &$array2 ): array
```

array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
    => array('key' => array('org value', 'new value'));

array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
Matching keys' values in the second array overwrite those in the first array, as is the
case with array_merge, i.e.:

array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
    => array('key' => array('new value'));

Parameters are passed by reference, though only for performance reasons. They're not
altered by this function.

* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array1` | **array** |  |
| `$array2` | **array** |  |




---

## DataMapper

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\models\DataMapper

**See Also:**

* https://github.com/NovemBit/i18n 

### getDB



```php
DataMapper::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

## DB

DB component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\system\component\DB
* Parent class: \NovemBit\i18n\system\Component

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
DB::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

{@inheritdoc}
Init method of component.

```php
DB::commonInit(  ): void
```

Setting default connection of DB





---

### isCli

Check if script running on CLI

```php
DB::isCli(  ): boolean
```







---

### getLogger



```php
DB::getLogger(  ): \Psr\Log\LoggerInterface
```







---

### setLogger



```php
DB::setLogger( \Psr\Log\LoggerInterface $logger )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logger` | **\Psr\Log\LoggerInterface** |  |




---

### getRuntimeDir



```php
DB::getRuntimeDir(  ): string
```







---

### setRuntimeDir



```php
DB::setRuntimeDir( string $runtime_dir )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$runtime_dir` | **string** |  |




---

### setCachePool



```php
DB::setCachePool( \Psr\SimpleCache\CacheInterface $cache_pool )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cache_pool` | **\Psr\SimpleCache\CacheInterface** |  |




---

### getCachePool



```php
DB::getCachePool(  ): \Psr\SimpleCache\CacheInterface
```







---

### defaultConfig



```php
DB::defaultConfig(  )
```



* This method is **static**.



---

### getConnection

Get connection of DB

```php
DB::getConnection(  ): \yii\db\Connection
```







---

### setConnection

Set connection of DB

```php
DB::setConnection( \yii\db\Connection $_connection ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_connection` | **\yii\db\Connection** | Yii2 db connection |




---

## DB

DB component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\db\DB
* Parent class: \NovemBit\i18n\system\Component

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
DB::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

{@inheritdoc}
Init method of component.

```php
DB::commonInit(  ): void
```

Setting default connection of DB





---

### isCli

Check if script running on CLI

```php
DB::isCli(  ): boolean
```







---

### getLogger



```php
DB::getLogger(  ): \Psr\Log\LoggerInterface
```







---

### setLogger



```php
DB::setLogger( \Psr\Log\LoggerInterface $logger )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logger` | **\Psr\Log\LoggerInterface** |  |




---

### getRuntimeDir



```php
DB::getRuntimeDir(  ): string
```







---

### setRuntimeDir



```php
DB::setRuntimeDir( string $runtime_dir )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$runtime_dir` | **string** |  |




---

### setCachePool



```php
DB::setCachePool( \Psr\SimpleCache\CacheInterface $cache_pool )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cache_pool` | **\Psr\SimpleCache\CacheInterface** |  |




---

### getCachePool



```php
DB::getCachePool(  ): \Psr\SimpleCache\CacheInterface
```







---

### defaultConfig



```php
DB::defaultConfig(  )
```



* This method is **static**.



---

### getConnection

Get connection of DB

```php
DB::getConnection(  ): \Doctrine\DBAL\Connection
```







---

### getConnectionParams

Connection params getter

```php
DB::getConnectionParams(  ): array
```







---

## Dummy

Dummy method of translation



* Full name: \NovemBit\i18n\component\translation\method\Dummy
* Parent class: \NovemBit\i18n\component\translation\method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

## Exception

System Exception class



* Full name: \NovemBit\i18n\system\exception\Exception
* Parent class: 
* This class implements: \NovemBit\i18n\system\exception\FriendlyExceptionInterface

**See Also:**

* https://github.com/NovemBit/i18n 

## Google

Google Translate method of translation



* Full name: \NovemBit\i18n\component\translation\method\Google
* Parent class: \NovemBit\i18n\component\translation\method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

### mainInit

{@inheritdoc}

```php
Google::mainInit(  ): void
```







---

## HTML

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\HTML
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### get

Main method to get translations from DB

```php
HTML::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
HTML::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

### getDB



```php
HTML::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

## HTML

HTML type for translation component



* Full name: \NovemBit\i18n\component\translation\type\HTML
* Parent class: \NovemBit\i18n\component\translation\type\XML
* This class implements: \NovemBit\i18n\component\translation\type\interfaces\HTML

**See Also:**

* https://github.com/NovemBit/i18n 

### defaultConfig

{@inheritDoc}

```php
HTML::defaultConfig(  ): array
```



* This method is **static**.



---

### getParserType



```php
HTML::getParserType(  ): integer
```







---

### setParserType



```php
HTML::setParserType( integer $parser_type )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$parser_type` | **integer** |  |




---

### buildToTranslateFields



```php
HTML::buildToTranslateFields( \DOMNode &$node, array $params, array &$data )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMNode** |  |
| `$params` | **array** |  |
| `$data` | **array** |  |




---

### replaceTranslatedFields



```php
HTML::replaceTranslatedFields( \DOMNode &$node, array $params, array &$data )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMNode** |  |
| `$params` | **array** |  |
| `$data` | **array** |  |




---

### getHelperAttributes

{@inheritDoc}

```php
HTML::getHelperAttributes(  ): boolean
```







---

### setHelperAttributes

{@inheritDoc}

```php
HTML::setHelperAttributes( boolean $status ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$status` | **boolean** | If true then
                    html translation including additional attributes |




---

### addBeforeParseCallback



```php
HTML::addBeforeParseCallback( callable $callback )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




---

### addAfterParseCallback



```php
HTML::addAfterParseCallback( callable $callback )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




---

### getBeforeParseCallbacks



```php
HTML::getBeforeParseCallbacks(  ): array
```







---

### getAfterParseCallbacks



```php
HTML::getAfterParseCallbacks(  ): array
```







---

## HTMLFragment

HTML type for translation component



* Full name: \NovemBit\i18n\component\translation\type\HTMLFragment
* Parent class: \NovemBit\i18n\component\translation\type\HTML
* This class implements: \NovemBit\i18n\component\translation\type\interfaces\HTMLFragment

**See Also:**

* https://github.com/NovemBit/i18n 

### defaultConfig

{@inheritDoc}

```php
HTMLFragment::defaultConfig(  ): array
```



* This method is **static**.



---

### getParserType



```php
HTMLFragment::getParserType(  ): integer
```







---

### setParserType



```php
HTMLFragment::setParserType( integer $parser_type )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$parser_type` | **integer** |  |




---

### buildToTranslateFields



```php
HTMLFragment::buildToTranslateFields( \DOMNode &$node, array $params, array &$data )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMNode** |  |
| `$params` | **array** |  |
| `$data` | **array** |  |




---

### replaceTranslatedFields



```php
HTMLFragment::replaceTranslatedFields( \DOMNode &$node, array $params, array &$data )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMNode** |  |
| `$params` | **array** |  |
| `$data` | **array** |  |




---

### getHelperAttributes

{@inheritDoc}

```php
HTMLFragment::getHelperAttributes(  ): boolean
```







---

### setHelperAttributes

{@inheritDoc}

```php
HTMLFragment::setHelperAttributes( boolean $status ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$status` | **boolean** | If true then
                    html translation including additional attributes |




---

### addBeforeParseCallback



```php
HTMLFragment::addBeforeParseCallback( callable $callback )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




---

### addAfterParseCallback



```php
HTMLFragment::addAfterParseCallback( callable $callback )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




---

### getBeforeParseCallbacks



```php
HTMLFragment::getBeforeParseCallbacks(  ): array
```







---

### getAfterParseCallbacks



```php
HTMLFragment::getAfterParseCallbacks(  ): array
```







---

## JSON

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\JSON
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### get

Main method to get translations from DB

```php
JSON::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
JSON::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

### getDB



```php
JSON::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

## LanguageException

Language Component Exception file



* Full name: \NovemBit\i18n\component\languages\exceptions\LanguageException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

## Method

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\method\models\Method
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### get

Main method to get translations from DB

```php
Method::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
Method::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

### getDB



```php
Method::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

## MethodException

Language Component Exception file



* Full name: \NovemBit\i18n\component\translation\method\exceptions\MethodException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

## Module

Module class
Main instance of i18n library. Should be used for any external connection
Provides component system. There have some required components,
DBAL (RDMS) configurations, Request handlers, Translation abstraction layer,

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\Module
* Parent class: \NovemBit\i18n\system\Component

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
Module::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonLateInit



```php
Module::commonLateInit(  ): void
```

Load Yii framework container to use some libraries that not
Allowed to use standalone





---

### mainInit

Component init method
Non CLI
Running after child component initialization

```php
Module::mainInit(  ): void
```







---

### isCli

Check if script running on CLI

```php
Module::isCli(  ): boolean
```







---

### getLogger



```php
Module::getLogger(  ): \Psr\Log\LoggerInterface
```







---

### setLogger



```php
Module::setLogger( \Psr\Log\LoggerInterface $logger )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logger` | **\Psr\Log\LoggerInterface** |  |




---

### getRuntimeDir



```php
Module::getRuntimeDir(  ): string
```







---

### setRuntimeDir



```php
Module::setRuntimeDir( string $runtime_dir )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$runtime_dir` | **string** |  |




---

### setCachePool



```php
Module::setCachePool( \Psr\SimpleCache\CacheInterface $cache_pool )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cache_pool` | **\Psr\SimpleCache\CacheInterface** |  |




---

### getCachePool



```php
Module::getCachePool(  ): \Psr\SimpleCache\CacheInterface
```







---

### defaultConfig

Default component configuration

```php
Module::defaultConfig(  ): array
```



* This method is **static**.



---

### start

Start request translation

```php
Module::start(  ): void
```







---

### instance

Creating module main instance

```php
Module::instance( null|array $config = null ): self
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **null&#124;array** | Main configuration array |




---

## RequestException

Request Exception class



* Full name: \NovemBit\i18n\component\request\exceptions\RequestException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

## Rest

Rest component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\rest\Rest
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\rest\interfaces\Rest

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
Rest::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### isCli

Check if script running on CLI

```php
Rest::isCli(  ): boolean
```







---

### getLogger



```php
Rest::getLogger(  ): \Psr\Log\LoggerInterface
```







---

### setLogger



```php
Rest::setLogger( \Psr\Log\LoggerInterface $logger )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logger` | **\Psr\Log\LoggerInterface** |  |




---

### getRuntimeDir



```php
Rest::getRuntimeDir(  ): string
```







---

### setRuntimeDir



```php
Rest::setRuntimeDir( string $runtime_dir )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$runtime_dir` | **string** |  |




---

### setCachePool



```php
Rest::setCachePool( \Psr\SimpleCache\CacheInterface $cache_pool )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cache_pool` | **\Psr\SimpleCache\CacheInterface** |  |




---

### getCachePool



```php
Rest::getCachePool(  ): \Psr\SimpleCache\CacheInterface
```







---

### defaultConfig



```php
Rest::defaultConfig(  )
```



* This method is **static**.



---

### start

Start rest request

```php
Rest::start(  ): void
```







---

### actionTranslate

Translate Action method

```php
Rest::actionTranslate(  ): array|integer
```







---

### actionIndex

Index Action method

```php
Rest::actionIndex(  ): array
```







---

### actionRestrict

Restrict Action method

```php
Rest::actionRestrict(  ): array
```







---

## Rest

Rest Translate method of translation



* Full name: \NovemBit\i18n\component\translation\method\Rest
* Parent class: \NovemBit\i18n\component\translation\method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

### getLanguagesConfig

Get languages configuration from main module instance `$config`

```php
Rest::getLanguagesConfig(  ): array
```







---

## Strings





* Full name: \NovemBit\i18n\system\helpers\Strings


### getStringsDifference

Get string difference

```php
Strings::getStringsDifference( string $before, string $after, string|null &$prefix = null, string|null &$suffix = null ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial type of string |
| `$after` | **string** | Final type of string |
| `$prefix` | **string&#124;null** | Referenced variable to receive difference prefix |
| `$suffix` | **string&#124;null** | Referenced variable to receive difference suffix |




---

## Text

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\Text
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### get

Main method to get translations from DB

```php
Text::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
Text::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

### getDB



```php
Text::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

## Translation

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\models\Translation
* Parent class: \NovemBit\i18n\models\DataMapper
* This class implements: \NovemBit\i18n\component\translation\models\interfaces\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### getDB



```php
Translation::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

### get

Main method to get translations from DB

```php
Translation::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
Translation::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

## TranslationException

Request Exception class



* Full name: \NovemBit\i18n\component\translation\exceptions\TranslationException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

## URL

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\URL
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### get

Main method to get translations from DB

```php
URL::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
URL::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

### getDB



```php
URL::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
URL::rules(  ): array
```







---

## URL

Helper class for some actions with URLs



* Full name: \NovemBit\i18n\system\helpers\URL

**See Also:**

* https://github.com/NovemBit/i18n 

### addQueryVars

Adding query parameters to URL

```php
URL::addQueryVars( string $url, string $name, string $value ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Initial url |
| `$name` | **string** | Parameter name (key) |
| `$value` | **string** | Value of parameter |




---

### removeQueryVars

Remove Query parameter from URL

```php
URL::removeQueryVars( string $url, string $paramName ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Initial url |
| `$paramName` | **string** | Parameter name (key) |




---

### buildUrl

Build url from parts
Same as reversed parse_url

```php
URL::buildUrl( array $parts ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$parts` | **array** | Parts of url |




---

## XML

HTML type for translation component



* Full name: \NovemBit\i18n\component\translation\type\XML
* Parent class: \NovemBit\i18n\component\translation\type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

### defaultConfig

{@inheritDoc}

```php
XML::defaultConfig(  ): array
```



* This method is **static**.



---

### getParserType



```php
XML::getParserType(  ): integer
```







---

### setParserType



```php
XML::setParserType( integer $parser_type )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$parser_type` | **integer** |  |




---

### buildToTranslateFields



```php
XML::buildToTranslateFields( \DOMNode &$node, array $params, array &$data )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMNode** |  |
| `$params` | **array** |  |
| `$data` | **array** |  |




---

### replaceTranslatedFields



```php
XML::replaceTranslatedFields( \DOMNode &$node, array $params, array &$data )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMNode** |  |
| `$params` | **array** |  |
| `$data` | **array** |  |




---

### getHelperAttributes

{@inheritDoc}

```php
XML::getHelperAttributes(  ): boolean
```







---

### setHelperAttributes

{@inheritDoc}

```php
XML::setHelperAttributes( boolean $status ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$status` | **boolean** | If true then
                    html translation including additional attributes |




---

### addBeforeParseCallback



```php
XML::addBeforeParseCallback( callable $callback )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




---

### addAfterParseCallback



```php
XML::addAfterParseCallback( callable $callback )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




---

### getBeforeParseCallbacks



```php
XML::getBeforeParseCallbacks(  ): array
```







---

### getAfterParseCallbacks



```php
XML::getAfterParseCallbacks(  ): array
```







---

## XML

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\XML
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### get

Main method to get translations from DB

```php
XML::get( array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
XML::saveTranslations( string $from_language, array $translations, integer $level, boolean $overwrite = false, array &$result = array() ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |
| `$overwrite` | **boolean** | Overwrite or not |
| `$result` | **array** |  |




---

### getDB



```php
XML::getDB(  ): \Doctrine\DBAL\Connection
```



* This method is **static**.



---



--------
> This document was automatically generated from source code comments on 2019-12-03 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
