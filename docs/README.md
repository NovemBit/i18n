# NovemBit i18n - Internationalization library

## Table of Contents

* [ActiveRecord](#activerecord)
    * [getDb](#getdb)
* [DataType](#datatype)
    * [isHTML](#ishtml)
    * [isURL](#isurl)
    * [isJSON](#isjson)
    * [getType](#gettype)
* [DB](#db)
    * [__construct](#__construct)
    * [commonInit](#commoninit)
    * [init](#init)
    * [cli](#cli)
    * [cliInit](#cliinit)
    * [getConnection](#getconnection)
    * [setConnection](#setconnection)
* [Dummy](#dummy)
    * [validateBeforeTranslate](#validatebeforetranslate)
    * [validateAfterTranslate](#validateaftertranslate)
    * [init](#init-1)
    * [beforeTranslate](#beforetranslate)
    * [afterTranslate](#aftertranslate)
    * [translate](#translate)
    * [reTranslate](#retranslate)
    * [beforeReTranslate](#beforeretranslate)
    * [afterReTranslate](#afterretranslate)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate)
    * [validateAllAfterReTranslate](#validateallafterretranslate)
    * [validateBeforeReTranslate](#validatebeforeretranslate)
    * [validateAfterReTranslate](#validateafterretranslate)
    * [validateAllBeforeTranslate](#validateallbeforetranslate)
    * [validateAllAfterTranslate](#validateallaftertranslate)
    * [doTranslate](#dotranslate)
    * [__construct](#__construct-1)
    * [commonInit](#commoninit-1)
    * [cli](#cli-1)
    * [cliInit](#cliinit-1)
* [Exception](#exception)
    * [errorMessage](#errormessage)
* [Google](#google)
    * [validateBeforeTranslate](#validatebeforetranslate-1)
    * [validateAfterTranslate](#validateaftertranslate-1)
    * [init](#init-2)
    * [beforeTranslate](#beforetranslate-1)
    * [afterTranslate](#aftertranslate-1)
    * [translate](#translate-1)
    * [reTranslate](#retranslate-1)
    * [beforeReTranslate](#beforeretranslate-1)
    * [afterReTranslate](#afterretranslate-1)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate-1)
    * [validateAllAfterReTranslate](#validateallafterretranslate-1)
    * [validateBeforeReTranslate](#validatebeforeretranslate-1)
    * [validateAfterReTranslate](#validateafterretranslate-1)
    * [validateAllBeforeTranslate](#validateallbeforetranslate-1)
    * [validateAllAfterTranslate](#validateallaftertranslate-1)
    * [doTranslate](#dotranslate-1)
    * [__construct](#__construct-2)
    * [commonInit](#commoninit-2)
    * [cli](#cli-2)
    * [cliInit](#cliinit-2)
* [HTML](#html)
    * [init](#init-3)
    * [beforeTranslate](#beforetranslate-2)
    * [afterTranslate](#aftertranslate-2)
    * [translate](#translate-2)
    * [reTranslate](#retranslate-2)
    * [beforeReTranslate](#beforeretranslate-2)
    * [afterReTranslate](#afterretranslate-2)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate-2)
    * [validateAllAfterReTranslate](#validateallafterretranslate-2)
    * [validateBeforeReTranslate](#validatebeforeretranslate-2)
    * [validateAfterReTranslate](#validateafterretranslate-2)
    * [validateBeforeTranslate](#validatebeforetranslate-2)
    * [validateAfterTranslate](#validateaftertranslate-2)
    * [validateAllBeforeTranslate](#validateallbeforetranslate-2)
    * [validateAllAfterTranslate](#validateallaftertranslate-2)
    * [doTranslate](#dotranslate-2)
    * [__construct](#__construct-3)
    * [commonInit](#commoninit-3)
    * [cli](#cli-3)
    * [cliInit](#cliinit-3)
    * [getHtmlParser](#gethtmlparser)
    * [setHtmlParser](#sethtmlparser)
* [HTML](#html-1)
    * [load](#load)
    * [fetch](#fetch)
    * [getDom](#getdom)
    * [setDom](#setdom)
    * [getTranslateFields](#gettranslatefields)
    * [addTranslateField](#addtranslatefield)
    * [getHtml](#gethtml)
    * [save](#save)
    * [setHtml](#sethtml)
    * [getQuery](#getquery)
    * [setQuery](#setquery)
* [JSON](#json)
    * [init](#init-4)
    * [beforeTranslate](#beforetranslate-3)
    * [afterTranslate](#aftertranslate-3)
    * [translate](#translate-3)
    * [reTranslate](#retranslate-3)
    * [beforeReTranslate](#beforeretranslate-3)
    * [afterReTranslate](#afterretranslate-3)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate-3)
    * [validateAllAfterReTranslate](#validateallafterretranslate-3)
    * [validateBeforeReTranslate](#validatebeforeretranslate-3)
    * [validateAfterReTranslate](#validateafterretranslate-3)
    * [validateBeforeTranslate](#validatebeforetranslate-3)
    * [validateAfterTranslate](#validateaftertranslate-3)
    * [validateAllBeforeTranslate](#validateallbeforetranslate-3)
    * [validateAllAfterTranslate](#validateallaftertranslate-3)
    * [doTranslate](#dotranslate-3)
    * [__construct](#__construct-4)
    * [commonInit](#commoninit-4)
    * [cli](#cli-4)
    * [cliInit](#cliinit-4)
* [Languages](#languages)
    * [__construct](#__construct-5)
    * [commonInit](#commoninit-5)
    * [init](#init-5)
    * [cli](#cli-5)
    * [cliInit](#cliinit-5)
    * [getLanguageFromUrl](#getlanguagefromurl)
    * [removeScriptNameFromUrl](#removescriptnamefromurl)
    * [addLanguageToUrl](#addlanguagetourl)
    * [validateLanguage](#validatelanguage)
    * [validateLanguages](#validatelanguages)
    * [getAcceptLanguages](#getacceptlanguages)
    * [getFromLanguage](#getfromlanguage)
    * [getDefaultLanguage](#getdefaultlanguage)
    * [getLanguageQueryKey](#getlanguagequerykey)
* [Module](#module)
    * [__construct](#__construct-6)
    * [commonInit](#commoninit-6)
    * [init](#init-6)
    * [cli](#cli-6)
    * [cliInit](#cliinit-6)
    * [start](#start)
    * [instance](#instance)
* [Request](#request)
    * [__construct](#__construct-7)
    * [commonInit](#commoninit-7)
    * [init](#init-7)
    * [cli](#cli-7)
    * [cliInit](#cliinit-7)
    * [getRefererSourceUrl](#getreferersourceurl)
    * [setRefererSourceUrl](#setreferersourceurl)
    * [getRefererTranslations](#getreferertranslations)
    * [setRefererTranslations](#setreferertranslations)
    * [getReferer](#getreferer)
    * [setReferer](#setreferer)
    * [getRefererLanguage](#getrefererlanguage)
    * [setRefererLanguage](#setrefererlanguage)
    * [translateBuffer](#translatebuffer)
    * [start](#start-1)
    * [getDestination](#getdestination)
    * [getSourceUrl](#getsourceurl)
    * [getUrlTranslations](#geturltranslations)
    * [getLanguage](#getlanguage)
    * [getTranslation](#gettranslation)
* [Rest](#rest)
    * [__construct](#__construct-8)
    * [commonInit](#commoninit-8)
    * [init](#init-8)
    * [cli](#cli-8)
    * [cliInit](#cliinit-8)
    * [start](#start-2)
    * [actionTranslate](#actiontranslate)
    * [actionIndex](#actionindex)
    * [actionRestrict](#actionrestrict)
* [RestMethod](#restmethod)
    * [validateBeforeTranslate](#validatebeforetranslate-4)
    * [validateAfterTranslate](#validateaftertranslate-4)
    * [init](#init-9)
    * [beforeTranslate](#beforetranslate-4)
    * [afterTranslate](#aftertranslate-4)
    * [translate](#translate-4)
    * [reTranslate](#retranslate-4)
    * [beforeReTranslate](#beforeretranslate-4)
    * [afterReTranslate](#afterretranslate-4)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate-4)
    * [validateAllAfterReTranslate](#validateallafterretranslate-4)
    * [validateBeforeReTranslate](#validatebeforeretranslate-4)
    * [validateAfterReTranslate](#validateafterretranslate-4)
    * [validateAllBeforeTranslate](#validateallbeforetranslate-4)
    * [validateAllAfterTranslate](#validateallaftertranslate-4)
    * [doTranslate](#dotranslate-4)
    * [__construct](#__construct-9)
    * [commonInit](#commoninit-9)
    * [cli](#cli-9)
    * [cliInit](#cliinit-9)
* [Rule](#rule)
    * [__construct](#__construct-10)
    * [getTags](#gettags)
    * [setTags](#settags)
    * [getAttrs](#getattrs)
    * [setAttrs](#setattrs)
    * [getTexts](#gettexts)
    * [setTexts](#settexts)
    * [validate](#validate)
    * [getMode](#getmode)
    * [setMode](#setmode)
* [Text](#text)
    * [init](#init-10)
    * [beforeTranslate](#beforetranslate-5)
    * [afterTranslate](#aftertranslate-5)
    * [translate](#translate-5)
    * [reTranslate](#retranslate-5)
    * [beforeReTranslate](#beforeretranslate-5)
    * [afterReTranslate](#afterretranslate-5)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate-5)
    * [validateAllAfterReTranslate](#validateallafterretranslate-5)
    * [validateBeforeReTranslate](#validatebeforeretranslate-5)
    * [validateAfterReTranslate](#validateafterretranslate-5)
    * [validateBeforeTranslate](#validatebeforetranslate-5)
    * [validateAfterTranslate](#validateaftertranslate-5)
    * [validateAllBeforeTranslate](#validateallbeforetranslate-5)
    * [validateAllAfterTranslate](#validateallaftertranslate-5)
    * [doTranslate](#dotranslate-5)
    * [__construct](#__construct-11)
    * [commonInit](#commoninit-10)
    * [cli](#cli-10)
    * [cliInit](#cliinit-10)
* [Translation](#translation)
    * [getDb](#getdb-1)
    * [tableName](#tablename)
    * [rules](#rules)
    * [behaviors](#behaviors)
    * [attributeLabels](#attributelabels)
    * [get](#get)
    * [saveTranslations](#savetranslations)
* [Translation](#translation-1)
    * [__construct](#__construct-12)
    * [commonInit](#commoninit-11)
    * [init](#init-11)
    * [cli](#cli-11)
    * [cliInit](#cliinit-11)
    * [setLanguages](#setlanguages)
    * [getLanguages](#getlanguages)
    * [getFromLanguage](#getfromlanguage-1)
* [URL](#url)
    * [init](#init-12)
    * [beforeTranslate](#beforetranslate-6)
    * [afterTranslate](#aftertranslate-6)
    * [translate](#translate-6)
    * [reTranslate](#retranslate-6)
    * [beforeReTranslate](#beforeretranslate-6)
    * [afterReTranslate](#afterretranslate-6)
    * [validateAllBeforeReTranslate](#validateallbeforeretranslate-6)
    * [validateAllAfterReTranslate](#validateallafterretranslate-6)
    * [validateBeforeReTranslate](#validatebeforeretranslate-6)
    * [validateAfterReTranslate](#validateafterretranslate-6)
    * [validateBeforeTranslate](#validatebeforetranslate-6)
    * [validateAfterTranslate](#validateaftertranslate-6)
    * [validateAllBeforeTranslate](#validateallbeforetranslate-6)
    * [validateAllAfterTranslate](#validateallaftertranslate-6)
    * [doTranslate](#dotranslate-6)
    * [__construct](#__construct-13)
    * [commonInit](#commoninit-12)
    * [cli](#cli-12)
    * [cliInit](#cliinit-12)
* [URL](#url-1)
    * [addQueryVars](#addqueryvars)
    * [removeQueryVars](#removequeryvars)
    * [buildUrl](#buildurl)

## ActiveRecord

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\models\ActiveRecord
* Parent class: 

**See Also:**

* https://github.com/NovemBit/i18n 

### getDb

Get DB of main module instance

```php
ActiveRecord::getDb(  ): \yii\db\Connection
```



* This method is **static**.



---

## DataType

Helper class for determine content types



* Full name: \NovemBit\i18n\system\helpers\DataType

**See Also:**

* https://github.com/NovemBit/i18n 

### isHTML

Check if string is HTML

```php
DataType::isHTML( string $string ): boolean
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | HTML string |




---

### isURL

Check if string is URL

```php
DataType::isURL( string $string ): mixed
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | URL string |




---

### isJSON

Check if string is JSON

```php
DataType::isJSON( string $string ): boolean
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | JSON string |




---

### getType

Get type of string
 URL, JSON, HTML

```php
DataType::getType( string $string, integer $default = self::UNDEFINED ): integer|string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | String content |
| `$default` | **integer** | Default type returning when type is unknown |




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

Common init method running before
Initialization of child components

```php
DB::commonInit(  ): void
```







---

### init

{@inheritdoc}
Init method of component.

```php
DB::init(  ): void
```

Setting default connection of DB





---

### cli

Action that will run
Only on cli script

```php
DB::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
DB::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




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

## Dummy

Dummy method of translation

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Method\Dummy
* Parent class: \NovemBit\i18n\component\translation\Method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

### validateBeforeTranslate

Validate before translate

```php
Dummy::validateBeforeTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Referenced text variable |




---

### validateAfterTranslate

Validate after translate

```php
Dummy::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial value of string |
| `$after` | **string** | final value of string |
| `$translates` | **array** | Referenced variable of already translated values |




---

### init

Component init method
Running after child component initialization

```php
Dummy::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
Dummy::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
Dummy::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
Dummy::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
Dummy::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
Dummy::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
Dummy::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
Dummy::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
Dummy::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate before ReTranslate

```php
Dummy::validateBeforeReTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Text to validate |




---

### validateAfterReTranslate

Validate after ReTranslate

```php
Dummy::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial value of string |
| `$after` | **string** | final value of string |
| `$result` | **array** | Referenced variable array of results |




---

### validateAllBeforeTranslate

Validate all before translate

```php
Dummy::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
Dummy::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translation method

```php
Dummy::doTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### __construct

Component constructor.

```php
Dummy::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
Dummy::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Dummy::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Dummy::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

## Exception

Main exception class



* Full name: \NovemBit\i18n\system\exception\Exception
* Parent class: 

**See Also:**

* https://github.com/NovemBit/i18n 

### errorMessage

Error message method

```php
Exception::errorMessage(  ): string
```







---

## Google

Google Translate method of translation

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Method\Google
* Parent class: \NovemBit\i18n\component\translation\Method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

### validateBeforeTranslate

Validate before translate

```php
Google::validateBeforeTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Referenced text variable |




---

### validateAfterTranslate

Validate after translate

```php
Google::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial value of string |
| `$after` | **string** | final value of string |
| `$translates` | **array** | Referenced variable of already translated values |




---

### init

{@inheritdoc}

```php
Google::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
Google::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
Google::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
Google::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
Google::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
Google::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
Google::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
Google::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
Google::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate before ReTranslate

```php
Google::validateBeforeReTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Text to validate |




---

### validateAfterReTranslate

Validate after ReTranslate

```php
Google::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial value of string |
| `$after` | **string** | final value of string |
| `$result` | **array** | Referenced variable array of results |




---

### validateAllBeforeTranslate

Validate all before translate

```php
Google::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
Google::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translate method

```php
Google::doTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### __construct

Component constructor.

```php
Google::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
Google::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Google::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Google::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

## HTML

HTML type for translation component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Type\HTML
* Parent class: \NovemBit\i18n\component\translation\Type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

### init

{@inheritdoc}

```php
HTML::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
HTML::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
HTML::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
HTML::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
HTML::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
HTML::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
HTML::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
HTML::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
HTML::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate before ReTranslate

```php
HTML::validateBeforeReTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Text to validate |




---

### validateAfterReTranslate

Validate after ReTranslate

```php
HTML::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial value of string |
| `$after` | **string** | final value of string |
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeTranslate

Validate before translate

```php
HTML::validateBeforeTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Referenced text variable |




---

### validateAfterTranslate

Validate after translate

```php
HTML::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial value of string |
| `$after` | **string** | final value of string |
| `$translates` | **array** | Referenced variable of already translated values |




---

### validateAllBeforeTranslate

Validate all before translate

```php
HTML::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
HTML::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translate method

```php
HTML::doTranslate( array $html_list ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html_list` | **array** | list of translatable HTML strings |




---

### __construct

Component constructor.

```php
HTML::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
HTML::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
HTML::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
HTML::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### getHtmlParser

Get Html parser

```php
HTML::getHtmlParser(  ): \NovemBit\i18n\system\parsers\HTML
```







---

### setHtmlParser

Set Html Parser

```php
HTML::setHtmlParser( \NovemBit\i18n\system\parsers\HTML $_html_parser ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_html_parser` | **\NovemBit\i18n\system\parsers\HTML** | Html parser |




---

## HTML

HTML parser with callback function
Using PHP Dom parser



* Full name: \NovemBit\i18n\system\parsers\HTML

**See Also:**

* https://github.com/NovemBit/i18n 

### load

HTML constructor.

```php
HTML::load( string $html ): \NovemBit\i18n\system\parsers\HTML
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html` | **string** | initial HTML content |




---

### fetch

Fetch current DOM document XPATH

```php
HTML::fetch( callable $text_callback, callable $attr_callback ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text_callback` | **callable** | Callback function for Text Nodes |
| `$attr_callback` | **callable** | Callback function for Attr Nodes |




---

### getDom

Get Dom (DomDocument)

```php
HTML::getDom(  ): \NovemBit\i18n\system\parsers\DomDocument
```







---

### setDom

Set Dom (DomDocument)

```php
HTML::setDom( \NovemBit\i18n\system\parsers\DomDocument $_dom ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_dom` | **\NovemBit\i18n\system\parsers\DomDocument** | Dom Document instance |




---

### getTranslateFields

Getting translate fields set

```php
HTML::getTranslateFields(  ): array&lt;mixed,\NovemBit\i18n\system\parsers\html\Rule&gt;
```







---

### addTranslateField

Adding translate fields

```php
HTML::addTranslateField( \NovemBit\i18n\system\parsers\html\Rule $rule, string $text = &#039;text&#039;, array $attrs = array() ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule` | **\NovemBit\i18n\system\parsers\html\Rule** | Rule object |
| `$text` | **string** | Text node type to translate |
| `$attrs` | **array** | List of attributes that must be translated |




---

### getHtml

Get HTML string

```php
HTML::getHtml(  ): mixed
```







---

### save

Save DomDocument final result as HTML

```php
HTML::save(  ): string|array&lt;mixed,string&gt;|null
```







---

### setHtml

Set HTML string

```php
HTML::setHtml( string $_html ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_html` | **string** | Initial HTML content |




---

### getQuery

Get Xpath query

```php
HTML::getQuery(  ): string
```







---

### setQuery

Set Xpath query

```php
HTML::setQuery( string $_query ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_query` | **string** | Query String |




---

## JSON

JSON type for translation component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Type\JSON
* Parent class: \NovemBit\i18n\component\translation\Type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

### init

Component init method
Running after child component initialization

```php
JSON::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
JSON::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
JSON::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
JSON::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
JSON::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
JSON::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
JSON::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
JSON::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
JSON::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate before ReTranslate

```php
JSON::validateBeforeReTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Text to validate |




---

### validateAfterReTranslate

Validate after ReTranslate

```php
JSON::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial value of string |
| `$after` | **string** | final value of string |
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeTranslate

Validate json string before translate

```php
JSON::validateBeforeTranslate( string &$json ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$json` | **string** | Json string |




---

### validateAfterTranslate

Validate after translate

```php
JSON::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial value of string |
| `$after` | **string** | final value of string |
| `$translates` | **array** | Referenced variable of already translated values |




---

### validateAllBeforeTranslate

Validate all before translate

```php
JSON::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
JSON::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translate method

```php
JSON::doTranslate( array $jsons ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$jsons` | **array** | Jsons string array |




---

### __construct

Component constructor.

```php
JSON::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
JSON::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
JSON::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
JSON::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

## Languages

Setting default languages
 from language - main website content language
 default language - default language for request
 accept languages - languages list for translations

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\languages\Languages
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\languages\Interfaces\Languages

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
Languages::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
Languages::commonInit(  ): void
```







---

### init

Component init method
Running after child component initialization

```php
Languages::init(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Languages::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Languages::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### getLanguageFromUrl

Get language code from url

```php
Languages::getLanguageFromUrl( string|null $url ): string|null
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string&#124;null** | Simple URL |




---

### removeScriptNameFromUrl

Remove executable file from url path

```php
Languages::removeScriptNameFromUrl( string $url ): mixed|string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Simple url |




---

### addLanguageToUrl

Adding language code to
Already translated URL

```php
Languages::addLanguageToUrl( string $url, string $language ): boolean|mixed|string
```

If @language_on_path is true then adding
Language code to beginning of @URL path

If @language_on_path is false or @URL contains
Script name or directory path then adding only
Query parameter of language


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Simple url |
| `$language` | **string** | language code |




---

### validateLanguage

Validate one language
Check if language exists in @accepted_languages array

```php
Languages::validateLanguage( string $language ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$language` | **string** | language code |




---

### validateLanguages

Validate list of Languages
Check if each language code exists on
Accepted languages list

```php
Languages::validateLanguages( array&lt;mixed,string&gt; $languages ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$languages` | **array<mixed,string>** | language codes |




---

### getAcceptLanguages

Get accepted languages array

```php
Languages::getAcceptLanguages( boolean $with_names = false ): array|null
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$with_names` | **boolean** | return languages with assoc keys and names |




---

### getFromLanguage

Get main from languages

```php
Languages::getFromLanguage(  ): mixed
```







---

### getDefaultLanguage

Get default language

```php
Languages::getDefaultLanguage(  ): string
```







---

### getLanguageQueryKey

Get language query key

```php
Languages::getLanguageQueryKey(  ): mixed
```







---

## Module

Module class

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

### commonInit

Common init method running before
Initialization of child components

```php
Module::commonInit(  ): void
```

Load Yii framework container to use some libraries that not
Allowed to use standalone





---

### init

Component init method
Running after child component initialization

```php
Module::init(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Module::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Module::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




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
Module::instance( null|array $config = null ): \NovemBit\i18n\Module
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **null&#124;array** | Main configuration array |




---

## Request

Main Request class.

It make easy to make requests flexible.
Determine type of received request.
Then provide translation for current type of content.

Using Translation component to translate received buffer content.

* Full name: \NovemBit\i18n\component\request\Request
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\request\Interfaces\Request

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
Request::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
Request::commonInit(  ): void
```







---

### init

Component init method
Running after child component initialization

```php
Request::init(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Request::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Request::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### getRefererSourceUrl

Get request referer source url

```php
Request::getRefererSourceUrl(  ): string
```







---

### setRefererSourceUrl

Set request referer source url

```php
Request::setRefererSourceUrl( string $_referer_source_url ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_referer_source_url` | **string** | Referer source url |




---

### getRefererTranslations

Get Referer translations

```php
Request::getRefererTranslations(  ): array
```







---

### setRefererTranslations

Set Referer translations

```php
Request::setRefererTranslations( array $_referer_translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_referer_translations` | **array** | Referer translations |




---

### getReferer

Get request referer

```php
Request::getReferer(  ): string
```







---

### setReferer

Set request referer

```php
Request::setReferer( string $_referer ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_referer` | **string** | Referer url |




---

### getRefererLanguage

Get Referer language

```php
Request::getRefererLanguage(  ): string
```







---

### setRefererLanguage

Set Referer Language

```php
Request::setRefererLanguage( mixed $_referer_language ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_referer_language` | **mixed** | Referer language |




---

### translateBuffer

Translate buffer of request content

```php
Request::translateBuffer( string $content ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** | content of request buffer |




---

### start

Start request translation

```php
Request::start(  ): void
```







---

### getDestination

Get Request Destination

```php
Request::getDestination(  ): string
```







---

### getSourceUrl

Get Source Url

```php
Request::getSourceUrl(  ): string
```







---

### getUrlTranslations

Get Url translations list

```php
Request::getUrlTranslations(  ): array
```







---

### getLanguage

Get Request current Language

```php
Request::getLanguage(  ): string
```







---

### getTranslation

Get Translation Component

```php
Request::getTranslation(  ): \NovemBit\i18n\component\translation\Translation
```







---

## Rest

Rest component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\rest\Rest
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\rest\Interfaces\Rest

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

### commonInit

Common init method running before
Initialization of child components

```php
Rest::commonInit(  ): void
```







---

### init

Component init method
Running after child component initialization

```php
Rest::init(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Rest::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Rest::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




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

## RestMethod

Rest Translate method of translation

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Method\RestMethod
* Parent class: \NovemBit\i18n\component\translation\Method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

### validateBeforeTranslate

Validate before translate

```php
RestMethod::validateBeforeTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Referenced text variable |




---

### validateAfterTranslate

Validate after translate

```php
RestMethod::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial value of string |
| `$after` | **string** | final value of string |
| `$translates` | **array** | Referenced variable of already translated values |




---

### init

Component init method
Running after child component initialization

```php
RestMethod::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
RestMethod::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
RestMethod::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
RestMethod::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
RestMethod::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
RestMethod::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
RestMethod::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
RestMethod::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
RestMethod::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate before ReTranslate

```php
RestMethod::validateBeforeReTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Text to validate |




---

### validateAfterReTranslate

Validate after ReTranslate

```php
RestMethod::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial value of string |
| `$after` | **string** | final value of string |
| `$result` | **array** | Referenced variable array of results |




---

### validateAllBeforeTranslate

Validate all before translate

```php
RestMethod::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
RestMethod::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translate method

```php
RestMethod::doTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### __construct

Component constructor.

```php
RestMethod::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
RestMethod::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
RestMethod::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
RestMethod::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

## Rule

HTML parser with callback function
Using PHP Dom parser



* Full name: \NovemBit\i18n\system\parsers\html\Rule

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Rule constructor.

```php
Rule::__construct( array|null $tags = null, array|null $attrs = null, array|null $texts = null, string $mode = self::IN )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tags` | **array&#124;null** | Tags array |
| `$attrs` | **array&#124;null** | Attributes array |
| `$texts` | **array&#124;null** | Texts array |
| `$mode` | **string** | Mode of join |




---

### getTags

Get Tags

```php
Rule::getTags(  ): mixed
```







---

### setTags

Set Tags

```php
Rule::setTags( array $_tags = null ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_tags` | **array** | Tags array |




---

### getAttrs

Get attributes

```php
Rule::getAttrs(  ): array
```







---

### setAttrs

Set attributes

```php
Rule::setAttrs( array $_attrs = null ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_attrs` | **array** | Attributes array |




---

### getTexts

Get Texts

```php
Rule::getTexts(  ): mixed
```







---

### setTexts

Set texts

```php
Rule::setTexts( mixed $_texts = null ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_texts` | **mixed** | Texts array |




---

### validate

Main Validate Method

```php
Rule::validate( \DOMElement $node ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$node` | **\DOMElement** | Node to validate |




---

### getMode

Get mode of join

```php
Rule::getMode(  ): mixed
```







---

### setMode

Setting join mode

```php
Rule::setMode( mixed $_mode ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_mode` | **mixed** | Join Mode |




---

## Text

Text type for Translation component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Type\Text
* Parent class: \NovemBit\i18n\component\translation\Type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

### init

Component init method
Running after child component initialization

```php
Text::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
Text::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
Text::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
Text::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
Text::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
Text::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
Text::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
Text::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
Text::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate before ReTranslate

```php
Text::validateBeforeReTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Text to validate |




---

### validateAfterReTranslate

Validate after ReTranslate

```php
Text::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Initial value of string |
| `$after` | **string** | final value of string |
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeTranslate

Validate text before translate

```php
Text::validateBeforeTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Referenced text variable to translate |




---

### validateAfterTranslate

Remove Whitespace

```php
Text::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | Before |
| `$after` | **string** | After |
| `$translates` | **array** | Last result |




---

### validateAllBeforeTranslate

Validate all before translate

```php
Text::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
Text::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translate method

```php
Text::doTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | List of texts to translate |




---

### __construct

Component constructor.

```php
Text::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
Text::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Text::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Text::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

## Translation

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\models\Translation
* Parent class: \NovemBit\i18n\models\ActiveRecord
* This class implements: \NovemBit\i18n\models\interfaces\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### getDb

Get DB of main module instance

```php
Translation::getDb(  ): \yii\db\Connection
```



* This method is **static**.



---

### tableName

Table name in DB

```php
Translation::tableName(  ): string
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
Translation::rules(  ): array
```







---

### behaviors

Yii component behaviours
 Using timestamp behaviour to set created and updated at
 Column values.

```php
Translation::behaviors(  ): array
```







---

### attributeLabels

Attribute values

```php
Translation::attributeLabels(  ): array
```







---

### get

Main method to get translations from DB

```php
Translation::get( integer $type, array $texts, string $from_language, array $to_languages, boolean $reverse = false ): array
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **integer** | Type of translated string |
| `$texts` | **array** | Texts array to translate |
| `$from_language` | **string** | From language |
| `$to_languages` | **array** | To languages list |
| `$reverse` | **boolean** | Use translate column as source (ReTranslate) |




---

### saveTranslations

Main method to save translations in DB

```php
Translation::saveTranslations( string $from_language, integer $type, array $translations, integer $level ): void
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | From language |
| `$type` | **integer** | Type of translations |
| `$translations` | **array** | Translations of texts |
| `$level` | **integer** | Level of translation |




---

## Translation

Translation component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Translation
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\translation\Interfaces\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### __construct

Component constructor.

```php
Translation::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
Translation::commonInit(  ): void
```







---

### init

Component init method
Running after child component initialization

```php
Translation::init(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
Translation::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
Translation::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### setLanguages

Set languages for translation

```php
Translation::setLanguages( array|string $_languages ): \NovemBit\i18n\component\translation\Translation
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$_languages` | **array&#124;string** | list of languages |




---

### getLanguages

Get current language

```php
Translation::getLanguages(  ): mixed
```







---

### getFromLanguage

Get from language from Languages component

```php
Translation::getFromLanguage(  ): mixed
```







---

## URL

Url translation component
Translate urls paths and build fully working url

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Type\URL
* Parent class: \NovemBit\i18n\component\translation\Type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

### init

Component init method
Running after child component initialization

```php
URL::init(  ): void
```







---

### beforeTranslate

Before translate method

```php
URL::beforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array |




---

### afterTranslate

After translate method

```php
URL::afterTranslate( array &$translations ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translations` | **array** | Translations array |




---

### translate

Method that must be used public for each time
To make translations,
Its using builtin caching system to
Save already translated texts on DB with Active data

```php
URL::translate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Texts array to translate |




---

### reTranslate

Re Translate already translated texts, find sources of
Bunch text strings

```php
URL::reTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### beforeReTranslate

Before Translate method

```php
URL::beforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### afterReTranslate

After ReTranslate method

```php
URL::afterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced array of results |




---

### validateAllBeforeReTranslate

Validate all before ReTranslate method

```php
URL::validateAllBeforeReTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts |




---

### validateAllAfterReTranslate

Validate all after ReTranslate

```php
URL::validateAllAfterReTranslate( array &$result ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$result` | **array** | Referenced variable array of results |




---

### validateBeforeReTranslate

Validate URL before ReTranslate

```php
URL::validateBeforeReTranslate( string &$url ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Re translatable URL |




---

### validateAfterReTranslate

Validate result after ReTranslate
 Remove language key from query variables

```php
URL::validateAfterReTranslate( string $before, string $after, array &$result ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial url |
| `$after` | **string** | final url |
| `$result` | **array** | Referenced variable to receive result |




---

### validateBeforeTranslate

Validate before translate
Take parts that must be preserved to concat
after translate paths

```php
URL::validateBeforeTranslate( string &$url ): boolean
```

Removing script name from url to make avoid
 that translatable part of url is only working path


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Translatable url |




---

### validateAfterTranslate

Validate after translate
Concat prefix, body and suffix to avoid that
Url is fully working

```php
URL::validateAfterTranslate( string $before, string $after, array &$translates ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$before` | **string** | initial type of url |
| `$after` | **string** | final type of url |
| `$translates` | **array** | list of translated urls |




---

### validateAllBeforeTranslate

Validate all before translate

```php
URL::validateAllBeforeTranslate( array &$texts ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### validateAllAfterTranslate

Validate all after translate

```php
URL::validateAllAfterTranslate( array &$translates ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$translates` | **array** | Array of translations |




---

### doTranslate

Doing translation method

```php
URL::doTranslate( array $urls ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$urls` | **array** | list of urls |




---

### __construct

Component constructor.

```php
URL::__construct( array $config = array(), null|\NovemBit\i18n\system\Component &$context = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration array |
| `$context` | **null&#124;\NovemBit\i18n\system\Component** | Context (parent) Component |




---

### commonInit

Common init method running before
Initialization of child components

```php
URL::commonInit(  ): void
```







---

### cli

Action that will run
Only on cli script

```php
URL::cli( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

### cliInit

Init method only for CLI

```php
URL::cliInit( array $argv, integer $argc ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argv` | **array** | Array of cli arguments |
| `$argc` | **integer** | Count of cli arguments |




---

## URL

Helper class for some actions with URLs



* Full name: \NovemBit\i18n\system\helpers\URL

**See Also:**

* https://github.com/NovemBit/i18n 

### addQueryVars

Adding query parameters to URL

```php
URL::addQueryVars( string $url, string $paramName, string $paramValue ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Initial url |
| `$paramName` | **string** | Parameter name (key) |
| `$paramValue` | **string** | Value of parameter |




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



--------
> This document was automatically generated from source code comments on 2019-10-04 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
