URL Crawler
==
The URL Crawler library facilitates data extraction from websites using specified URLs and content elements, and it integrates with the Price Sync extension for synchronizing product attribute.

Usage
-----

```php
use WebVision\Scrapper;

...
$dom = Scrapper::str_get_html( $string );
$elems = $dom->find($ele);
...

```
