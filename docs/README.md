# NovemBit i18n - Internationalization library

## Table of Contents

* [ActiveRecord](#activerecord)
    * [getDb](#getdb)
* [ActiveRecordException](#activerecordexception)
* [DataType](#datatype)
    * [isHTML](#ishtml)
    * [isURL](#isurl)
    * [isJSON](#isjson)
    * [getType](#gettype)
    * [getStringsDifference](#getstringsdifference)
* [DB](#db)
    * [__construct](#__construct)
    * [commonInit](#commoninit)
    * [init](#init)
    * [cli](#cli)
    * [cliInit](#cliinit)
    * [getConnection](#getconnection)
    * [setConnection](#setconnection)
* [Dummy](#dummy)
    * [doTranslate](#dotranslate)
* [Dynamic](#dynamic)
    * [doTranslate](#dotranslate-1)
    * [getType](#gettype-1)
* [Exception](#exception)
* [Google](#google)
    * [init](#init-1)
    * [doTranslate](#dotranslate-2)
* [HTML](#html)
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
* [HTML](#html-1)
    * [tableName](#tablename)
    * [rules](#rules)
    * [beforeSave](#beforesave)
    * [behaviors](#behaviors)
    * [attributeLabels](#attributelabels)
    * [get](#get)
    * [saveTranslations](#savetranslations)
    * [getDb](#getdb-1)
* [HTML](#html-2)
    * [init](#init-2)
    * [doTranslate](#dotranslate-3)
* [JSON](#json)
    * [doTranslate](#dotranslate-4)
    * [validateBeforeTranslate](#validatebeforetranslate)
* [JSON](#json-1)
    * [tableName](#tablename-1)
    * [rules](#rules-1)
    * [beforeSave](#beforesave-1)
    * [behaviors](#behaviors-1)
    * [attributeLabels](#attributelabels-1)
    * [get](#get-1)
    * [saveTranslations](#savetranslations-1)
    * [getDb](#getdb-2)
* [LanguageException](#languageexception)
* [Languages](#languages)
    * [__construct](#__construct-1)
    * [commonInit](#commoninit-1)
    * [init](#init-3)
    * [cli](#cli-1)
    * [cliInit](#cliinit-1)
    * [getLanguageFromUrl](#getlanguagefromurl)
    * [removeScriptNameFromUrl](#removescriptnamefromurl)
    * [addLanguageToUrl](#addlanguagetourl)
    * [validateLanguage](#validatelanguage)
    * [validateLanguages](#validatelanguages)
    * [getAcceptLanguages](#getacceptlanguages)
    * [getFromLanguage](#getfromlanguage)
    * [getDefaultLanguage](#getdefaultlanguage)
    * [getLanguageQueryKey](#getlanguagequerykey)
* [Method](#method)
    * [tableName](#tablename-2)
    * [rules](#rules-2)
    * [beforeSave](#beforesave-2)
    * [behaviors](#behaviors-2)
    * [attributeLabels](#attributelabels-2)
    * [get](#get-2)
    * [saveTranslations](#savetranslations-2)
    * [getDb](#getdb-3)
* [MethodException](#methodexception)
* [Module](#module)
    * [__construct](#__construct-2)
    * [commonInit](#commoninit-2)
    * [init](#init-4)
    * [cli](#cli-2)
    * [cliInit](#cliinit-2)
    * [start](#start)
    * [instance](#instance)
* [Request](#request)
    * [__construct](#__construct-3)
    * [commonInit](#commoninit-3)
    * [init](#init-5)
    * [cli](#cli-3)
    * [cliInit](#cliinit-3)
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
    * [getFromLanguage](#getfromlanguage-1)
    * [setFromLanguage](#setfromlanguage)
* [RequestException](#requestexception)
* [Rest](#rest)
    * [__construct](#__construct-4)
    * [commonInit](#commoninit-4)
    * [init](#init-6)
    * [cli](#cli-4)
    * [cliInit](#cliinit-4)
    * [start](#start-2)
    * [actionTranslate](#actiontranslate)
    * [actionIndex](#actionindex)
    * [actionRestrict](#actionrestrict)
* [Rule](#rule)
    * [__construct](#__construct-5)
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
    * [doTranslate](#dotranslate-5)
    * [validateAfterTranslate](#validateaftertranslate)
    * [validateBeforeTranslate](#validatebeforetranslate-1)
* [Text](#text-1)
    * [tableName](#tablename-3)
    * [rules](#rules-3)
    * [beforeSave](#beforesave-3)
    * [behaviors](#behaviors-3)
    * [attributeLabels](#attributelabels-3)
    * [get](#get-3)
    * [saveTranslations](#savetranslations-3)
    * [getDb](#getdb-4)
* [Translation](#translation)
    * [getDb](#getdb-5)
    * [tableName](#tablename-4)
    * [rules](#rules-4)
    * [beforeSave](#beforesave-4)
    * [behaviors](#behaviors-4)
    * [attributeLabels](#attributelabels-4)
    * [get](#get-4)
    * [saveTranslations](#savetranslations-4)
* [Translation](#translation-1)
    * [__construct](#__construct-6)
    * [commonInit](#commoninit-5)
    * [init](#init-7)
    * [cli](#cli-5)
    * [cliInit](#cliinit-5)
    * [setLanguages](#setlanguages)
    * [getLanguages](#getlanguages)
    * [getFromLanguage](#getfromlanguage-2)
* [TranslationException](#translationexception)
* [URL](#url)
    * [doTranslate](#dotranslate-6)
    * [validateAfterTranslate](#validateaftertranslate-1)
    * [validateBeforeTranslate](#validatebeforetranslate-2)
    * [validateBeforeReTranslate](#validatebeforeretranslate)
    * [validateAfterReTranslate](#validateafterretranslate)
* [URL](#url-1)
    * [addQueryVars](#addqueryvars)
    * [removeQueryVars](#removequeryvars)
    * [buildUrl](#buildurl)
* [URL](#url-2)
    * [tableName](#tablename-5)
    * [rules](#rules-5)
    * [beforeSave](#beforesave-5)
    * [behaviors](#behaviors-5)
    * [attributeLabels](#attributelabels-5)
    * [get](#get-5)
    * [saveTranslations](#savetranslations-5)
    * [getDb](#getdb-6)

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

## ActiveRecordException

Active Record Exception Class



* Full name: \NovemBit\i18n\models\exceptions\ActiveRecordException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

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
DataType::getType( string $string, integer $default = self::UNDEFINED ): integer
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | String content |
| `$default` | **integer** | Default type returning when type is unknown |




---

### getStringsDifference

Get string difference

```php
DataType::getStringsDifference( string $before, string $after, string|null &$prefix = null, string|null &$suffix = null ): void
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



* Full name: \NovemBit\i18n\component\translation\method\Dummy
* Parent class: \NovemBit\i18n\component\translation\method\Method

**See Also:**

* https://github.com/NovemBit/i18n 

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

## Dynamic

Rest Translate method of translation



* Full name: \NovemBit\i18n\component\translation\rest\Dynamic
* Parent class: 
* This class implements: \NovemBit\i18n\component\translation\interfaces\Rest

**See Also:**

* https://github.com/NovemBit/i18n 

### doTranslate

Doing translate method

```php
Dynamic::doTranslate( array $texts ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$texts` | **array** | Array of texts to translate |




---

### getType

Get type of current translation

```php
Dynamic::getType(  ): string
```







---

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

### init

{@inheritdoc}

```php
Google::init(  ): void
```







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
HTML::getDom(  ): \DOMDocument
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
HTML::getHtml(  ): string
```







---

### save

Save DomDocument final result as HTML

```php
HTML::save(  ): string
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

## HTML

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\HTML
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### tableName

Table name in DB

```php
HTML::tableName(  ): string
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
HTML::rules(  ): array
```







---

### beforeSave

Before save set type of node

```php
HTML::beforeSave( boolean $insert ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$insert` | **boolean** | if insert |




---

### behaviors

Yii2 component behaviours
Using timestamp behaviour
To set created and updated at columns values.

```php
HTML::behaviors(  ): array
```







---

### attributeLabels

Attribute values

```php
HTML::attributeLabels(  ): array
```







---

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
| `$overwrite` | **boolean** | If translation exists, then overwrite value |
| `$result` | **array** | Result about saving |




---

### getDb

Get DB of main module instance

```php
HTML::getDb(  ): \yii\db\Connection
```



* This method is **static**.



---

## HTML

HTML type for translation component



* Full name: \NovemBit\i18n\component\translation\type\HTML
* Parent class: \NovemBit\i18n\component\translation\type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

### init

{@inheritdoc}

```php
HTML::init(  ): void
```







---

### doTranslate

Doing translate method
Getting node values from two type of DOMNode

```php
HTML::doTranslate( array $html_list ): mixed
```

* DOMText - text content of parent node
* DOMAttr - attrs values of parent node

Then using callbacks for decode html entities
And send to translation:
Using custom type of translation for each type of node


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html_list` | **array** | list of translatable HTML strings |



**See Also:**

* \NovemBit\i18n\component\translation\type\DOMText * \NovemBit\i18n\component\translation\type\DOMAttr 

---

## JSON

JSON type for translation component



* Full name: \NovemBit\i18n\component\translation\type\JSON
* Parent class: \NovemBit\i18n\component\translation\type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

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

## JSON

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\JSON
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### tableName

Table name in DB

```php
JSON::tableName(  ): string
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
JSON::rules(  ): array
```







---

### beforeSave

Before save set type of node

```php
JSON::beforeSave( boolean $insert ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$insert` | **boolean** | if insert |




---

### behaviors

Yii2 component behaviours
Using timestamp behaviour
To set created and updated at columns values.

```php
JSON::behaviors(  ): array
```







---

### attributeLabels

Attribute values

```php
JSON::attributeLabels(  ): array
```







---

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
| `$overwrite` | **boolean** | If translation exists, then overwrite value |
| `$result` | **array** | Result about saving |




---

### getDb

Get DB of main module instance

```php
JSON::getDb(  ): \yii\db\Connection
```



* This method is **static**.



---

## LanguageException

Language Component Exception file



* Full name: \NovemBit\i18n\component\languages\exceptions\LanguageException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

## Languages

Setting default languages
 from language - main website content language
 default language - default language for request
 accept languages - languages list for translations

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\languages\Languages
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\languages\interfaces\Languages

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

## Method

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\method\models\Method
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### tableName

Table name in DB

```php
Method::tableName(  ): string
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
Method::rules(  ): array
```







---

### beforeSave

Before save set type of node

```php
Method::beforeSave( boolean $insert ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$insert` | **boolean** | if insert |




---

### behaviors

Yii2 component behaviours
Using timestamp behaviour
To set created and updated at columns values.

```php
Method::behaviors(  ): array
```







---

### attributeLabels

Attribute values

```php
Method::attributeLabels(  ): array
```







---

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
| `$overwrite` | **boolean** | If translation exists, then overwrite value |
| `$result` | **array** | Result about saving |




---

### getDb

Get DB of main module instance

```php
Method::getDb(  ): \yii\db\Connection
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

Request component main class.

# Meaning of Request component
It make easy to make requests flexible.
Determine type of received request.
Then provide translation for current type of content.

> Using Translation component to translate received buffer content.

* Full name: \NovemBit\i18n\component\request\Request
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\request\interfaces\Request

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

### getFromLanguage

Get main content language

```php
Request::getFromLanguage(  ): string
```







---

### setFromLanguage

Set main content language

```php
Request::setFromLanguage( string $from_language ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$from_language` | **string** | Language code |




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



* Full name: \NovemBit\i18n\component\translation\type\Text
* Parent class: \NovemBit\i18n\component\translation\type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

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

### validateAfterTranslate

Reset whitespace

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

### validateBeforeTranslate

Using dont_translate_patterns to ignore texts
Clearing whitespace

```php
Text::validateBeforeTranslate( string &$text ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** | Referenced text variable to translate |




---

## Text

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\Text
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### tableName

Table name in DB

```php
Text::tableName(  ): string
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
Text::rules(  ): array
```







---

### beforeSave

Before save set type of node

```php
Text::beforeSave( boolean $insert ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$insert` | **boolean** | if insert |




---

### behaviors

Yii2 component behaviours
Using timestamp behaviour
To set created and updated at columns values.

```php
Text::behaviors(  ): array
```







---

### attributeLabels

Attribute values

```php
Text::attributeLabels(  ): array
```







---

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
| `$overwrite` | **boolean** | If translation exists, then overwrite value |
| `$result` | **array** | Result about saving |




---

### getDb

Get DB of main module instance

```php
Text::getDb(  ): \yii\db\Connection
```



* This method is **static**.



---

## Translation

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\models\Translation
* Parent class: \NovemBit\i18n\models\ActiveRecord
* This class implements: \NovemBit\i18n\component\translation\models\interfaces\Translation

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

### beforeSave

Before save set type of node

```php
Translation::beforeSave( boolean $insert ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$insert` | **boolean** | if insert |




---

### behaviors

Yii2 component behaviours
Using timestamp behaviour
To set created and updated at columns values.

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
| `$overwrite` | **boolean** | If translation exists, then overwrite value |
| `$result` | **array** | Result about saving |




---

## Translation

Translation component

Its simple but provides very useful functionality
Module class

* Full name: \NovemBit\i18n\component\translation\Translation
* Parent class: \NovemBit\i18n\system\Component
* This class implements: \NovemBit\i18n\component\translation\interfaces\Translation

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
Translation::setLanguages( array|string $_languages ): self
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
Translation::getFromLanguage(  ): string
```







---

## TranslationException

Request Exception class



* Full name: \NovemBit\i18n\component\translation\exceptions\TranslationException
* Parent class: \NovemBit\i18n\system\exception\Exception

**See Also:**

* https://github.com/NovemBit/i18n 

## URL

Url translation component
Translate urls paths and build fully working url



* Full name: \NovemBit\i18n\component\translation\type\URL
* Parent class: \NovemBit\i18n\component\translation\type\Type

**See Also:**

* https://github.com/NovemBit/i18n 

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

## URL

ActiveRecord class. Child of Yii ActiveRecord library



* Full name: \NovemBit\i18n\component\translation\type\models\URL
* Parent class: \NovemBit\i18n\component\translation\models\Translation

**See Also:**

* https://github.com/NovemBit/i18n 

### tableName

Table name in DB

```php
URL::tableName(  ): string
```



* This method is **static**.



---

### rules

{@inheritdoc}

```php
URL::rules(  ): array
```







---

### beforeSave

Before save set type of node

```php
URL::beforeSave( boolean $insert ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$insert` | **boolean** | if insert |




---

### behaviors

Yii2 component behaviours
Using timestamp behaviour
To set created and updated at columns values.

```php
URL::behaviors(  ): array
```







---

### attributeLabels

Attribute values

```php
URL::attributeLabels(  ): array
```







---

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
| `$overwrite` | **boolean** | If translation exists, then overwrite value |
| `$result` | **array** | Result about saving |




---

### getDb

Get DB of main module instance

```php
URL::getDb(  ): \yii\db\Connection
```



* This method is **static**.



---



--------
> This document was automatically generated from source code comments on 2019-10-23 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
