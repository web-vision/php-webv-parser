<?php

namespace WebVision\Scrapper;

require 'simple_html_dom.php';

class HtmlParser {

	static public function file_get_html() {
		return call_user_func_array ( 'file_get_html' , func_get_args() );
	}

	static public function str_get_html() {
		return call_user_func_array ( 'str_get_html' , func_get_args() );
	}
}
