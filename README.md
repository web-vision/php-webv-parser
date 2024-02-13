## PHP Scrapper

PHP Scrapper is an HTML DOM parser written in PHP, enabling easy manipulation of HTML content. It is derived from the [PHP Simple HTML DOM Parser](https://simplehtmldom.sourceforge.io/docs/1.9/index.html) project. Additionally, the URL Crawler library aids in data extraction from websites by utilizing specified URLs and content elements.

### Install via composer

```composer require webvision/php-webv-parser```

### Usage

```php
use WebVision\Scrapper;

...
$dom = Scrapper::str_get_html( $string );
$elems = $dom->find($ele);
...

```
