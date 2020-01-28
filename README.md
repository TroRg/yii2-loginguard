# yii2-loginguard
Simple login form protection from password bruteforce.


## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist trorg/yii2-loginguard:"1.*"
```

or add

```json
"trorg/yii2-loginguard": "1.*"
```

to the require section of your composer.json.


## Configuration

To use this extension, you have to configure the behavior for your login form:

```php
<?php

use yii\base\Model;
use yii\helpers\ArrayHelper;
use trorg\yii2\loginguard\LoginGuardBehavior;

class LoginForm extends Model
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'loginguard' => [
                'class' => LoginGuardBehavior::class,
                'cache' => \Yii::$app->cache,
            ],
        ]);
    }
}
```

## Options
    - [`cache`](#cache)
    - [`attempts`](#attempts)
    - [`loginPeriod`](#loginPeriod)
    - [`blockPeriod`](#blockPeriod)
    - [`usernameAttribute`](#usernameAttribute)
    - [`passwordAttribute`](#passwordAttribte)
    - [`message`](#message)

### `cache` \yii\caching\CacheInterface | string
Name or instance of cache component.
Default value: *cache*

### `attempts` int
Maximum login attempts in [`login period`](#loginPeriod)
Defaul value: *3*

### `loginPeriod` int
Login period duration in seconds.
Defaul value: *300*

### `blockPeriod` int
Block login period duration in seconds.
Defaul value: *600*

### `usernameAttribute` string
Username attribute name in form.
Defaul value: *login*

### `passwordAttribute` string
Password attribute name in form.
Defaul value: *password*

### `message` string
Error message to display in form for *passwordAttribute* field.

