# TekinTCaptcha PHP client library
## 基于Google PHP项目设计思想实现的腾讯滑动智能验证码实现PHP中间件。

[![Build Status](https://travis-ci.org/tekintian/tekintcaptcha.svg)](https://travis-ci.org/tekintian/tekintcaptcha)
[![Latest Stable Version](https://poser.pugx.org/tekintian/tekintcaptcha/v/stable.svg)](https://packagist.org/packages/tekintian/tekintcaptcha)
[![Total Downloads](https://poser.pugx.org/tekintian/tekintcaptcha/downloads.svg)](https://packagist.org/packages/tekintian/tekintcaptcha)

* Project page: http://tekin.yunnan.ws/TekinTCaptcha/
* Repository: https://github.com/tekintian/TekinTCaptcha
* Version: 1.0.0
* License: BSD

## Description
腾讯验证码, 腾讯防水墙, 验证码, 滑动验证码, 智能滑动验证码PHP扩展, TekinTCaptcha - A Client library for Tencent Captcha, a service that protect websites from spam and abuse.
TekinTCaptcha is a free CAPTCHA service that protect websites from spam and abuse.

This is Tencent Captcha code that provides plugins for third-party integration with TekinTCaptcha.

## Installation

### Composer (Recommended)

[Composer](https://getcomposer.org/) is a widely used dependency manager for PHP
packages. This TekinTCaptcha client is available on Packagist as
[`google/recaptcha`](https://packagist.org/packages/tekintian/tekintcaptcha) and can be
installed either by running the `composer require` command or adding the library
to your `composer.json`. To enable Composer for you project, refer to the
project's [Getting Started](https://getcomposer.org/doc/00-intro.md)
documentation.

To add this dependency using the command, run the following from within your
project directory:
```
composer require tekintian/tekintcaptcha "~1.0"
```

Alternatively, add the dependency directly to your `composer.json` file:
```json
"require": {
    "tekintian/tekintcaptcha": "~1.0"
}
```

### Direct download (no Composer)

If you wish to install the library manually (i.e. without Composer), then you
can use the links on the main project page to either clone the repo or download
the [ZIP file](https://github.com/tekintian/TekinTCaptcha/archive/master.zip). For
convenience, an autoloader script is provided in `src/autoload.php` which you
can require into your script instead of Composer's `vendor/autoload.php`. For
example:

```php
require('/path/to/TekinTCaptcha/src/autoload.php');
$recaptcha = new \TekinTCaptcha\TekinTCaptcha($aid,$AppSecretKey);
```

The classes in the project are structured according to the
[PSR-4](http://www.php-fig.org/psr/psr-4/) standard, so you may of course also
use your own autoloader or require the needed files directly in your code.

### Development install

If you would like to contribute to this project or run the unit tests on within
your own environment you will need to install the development dependencies, in
this case that means [PHPUnit](https://phpunit.de/). If you clone the repo and
run `composer install` from within the repo, this will also grab PHPUnit and all
its dependencies for you. If you only need the autoloader installed, then you
can always specify to Composer not to run in development mode, e.g. `composer
install --no-dev`.

*Note:* These dependencies are only required for development, there's no
requirement for them to be included in your production code.

## Usage

First, register keys for your site at http://007.qq.com

When your app receives a form submission containing the `Ticket, Randstr`
field, you can verify it using:
```php
<?php
$recaptcha = new \TekinTCaptcha\TekinTCaptcha($aid,$AppSecretKey);
$resp = $recaptcha->verify($Ticket, $Randstr, $UserIP);
if ($resp->isSuccess()) {
    // verified!
    // if Domain Name Validation turned off don't forget to check hostname field
    // if($resp->getStatus() === 1) {  }
} else {
    $errors = $resp->getErrMsg();
}
```

You can see an end-to-end working example in
[examples/index.html](examples/index.html)
[examples/login.php](examples/login.php)


# 打赏赞助 Donate
![Donate with alipay](alipay_donate.jpg "支付宝打赏赞助, Donate with Alipay")