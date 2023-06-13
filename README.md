<p align="center">
    <a href="https://github.com/yii1tech" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/134691944" height="100px">
    </a>
    <h1 align="center">Dummy Session Extension for Yii 1</h1>
    <br>
</p>

This extension provides a mock for the standard Yii session, which avoids direct operations over PHP standard session.

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://img.shields.io/packagist/v/yii1tech/session-dummy.svg)](https://packagist.org/packages/yii1tech/session-dummy)
[![Total Downloads](https://img.shields.io/packagist/dt/yii1tech/session-dummy.svg)](https://packagist.org/packages/yii1tech/session-dummy)
[![Build Status](https://github.com/yii1tech/session-dummy/workflows/build/badge.svg)](https://github.com/yii1tech/session-dummy/actions)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii1tech/session-dummy
```

or add

```json
"yii1tech/session-dummy": "*"
```

to the "require" section of your composer.json.


Usage
-----

This extension provides a mock for the standard Yii session, which avoids direct operations over PHP standard session.
It introduces `\yii1tech\session\dummy\DummySession` class, which does not actually store session data anywhere, except current
process's memory, and avoid sending any headers to HTTP response.

This class is useful while writing unit tests, as it avoids sending headers and cookies to the StdOut.

Application configuration example:

```php
<?php

return [
    'name' => 'Test Application',
    'components' => [
        'session' => [
            'class' => yii1tech\session\dummy\DummySession::class,
        ],
        // ...
    ],
    // ...
];
```

This extension may also come in handy in API development. For example: if you need to authenticate user via OAuth token, but
keep tracking him in the code using `\CWebUser` abstraction. In this case you may switch session component "on the fly".
For example:

```php
<?php

namespace app\web\controllers;

use app\oauth\AuthUserByTokenFilter;
use CController;
use Yii;
use yii1tech\session\dummy\DummySession;

class ApiController extends CController
{
    public function init()
    {
        parent::init();
        
        Yii::app()->setComponent('session', new DummySession(), false); // mock session, so it does not send any Cookies to the API client
    }
    
    public function filters()
    {
        return [
            AuthUserByTokenFilter::class, // use custom identity to authenticate user via OAuth token inside {@see CWebUser}
            'accessControl', // now we can freely use standard "access control" filter and other features
        ];
    }
    
    public function accessRules()
    {
        return [
            // ...
        ];
    }
    
    // ...
}
```
