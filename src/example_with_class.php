<?php

use WebVision\Scrapper;
//$dom = HtmlDomParser::str_get_html( "<div class='test'>sdfsdgdf<label class='lbl'>345345</label></div>" );
//$elems = $dom->find('.test');

function scraping_generic($url, $search) {
	$return = false;

	$searchNode = getMainSelector($search);

	if(!$searchNode){
		echo "Please provide html again";
		return;
	}

	$html = HtmlDomParser::file_get_html($url);
	$data = getDataFromUrl($html, $searchNode, $search);
	// create HTML DOM
	if(!empty($data)){
	 	// get article block
		foreach($data as $found) {
			//echo $found->dump();
			// Found at least one.
			$return - true;
			$dom = HtmlDomParser::str_get_html($found);
			$dom->set_callback('handleElement');
			$dom->__toString();
		}
		// clean up memory
		$html->clear();
		unset($html);
	}


	 return $return;
}

function getDataFromUrl($html, $searchNode, $search){
	$out = $html->find($searchNode);

	if(empty($out)){
		$secondaryElement = getSecondarySelector($search);
		$out = $html->find($secondaryElement);
	}
	return $out;
}

function handleElement($elem)
{
	//echo $elem->dump();
    if($elem->tag == 'text') {
		$singletext = $elem->innertext();
		if(checkIfsingleNumberFound($singletext)){
			echo $finalString = removeAllButNumbers($singletext);
			die();
		}
    }
}

function checkIfsingleNumberFound($string){
	if (preg_match('~[0-9]+~', $string)) {
		return true;
	}
	return false;
}

function removeAllButNumbers($string)
{
    $removedNumbers = preg_replace('/[^0-9-.,]+/', '', $string);
    return str_replace(",", ".", $removedNumbers);
}

function getMainSelector($serach)
{
	$dom = HtmlDomParser::str_get_html($serach);
	$mainSelector = $dom->root->first_child();
	if($mainSelector->tag){
		if($mainSelector->class){
			$classJoin = str_replace(" ", ".", $mainSelector->class);
			return $mainSelector->tag . "." . $classJoin;
		}
		if($mainSelector->id){
			return $mainSelector->tag . "." . $mainSelector->id;
		}
	}

	return false;

}

function getSecondarySelector($serach)
{
	$dom = HtmlDomParser::str_get_html($serach);
	$mainSelector = $dom->root->first_child();
	if($mainSelector->tag && $mainSelector->class){
		$classJoin = explode(" ",$mainSelector->class)[0];
		return $mainSelector->tag . "." . $classJoin;
	}

	return false;

}

// ------------------------------------------
error_log ("post:" . print_r($_POST, true));
$url = "";
if (isset($_POST['url']))
{
	$url = $_POST['url'];
}
$search = "";
if (isset($_POST['search']))
{
	$search = $_POST['search'];
}
?>
<style>
	form.main_form {
    padding: 10px;
    background: 10px;
    display: inline-block;
    background-color: #f7f7f7;
    border: 1px solid #ddd;
}

form.main_form input,form.main_form textarea {
    width: 500px;
    min-height: 50px;
    margin: 10px 0px;
}

input[type="submit"] {
    display: block;
    width: 100%;
    margin: 0 auto;
}

textarea {
    height: 200px;
}
.example {
    background-color: #f7f7f7;
    border: 1px solid #ddd;
    margin: 40px 0px;
	padding: 5px;
}
.example label {
    color: #b66000;
    font-weight: bold;
    font-size: 30px;
}
pre {
    font-size: 15px;
    border: 5px solid #ddd;
    padding: 20px;
}
label {
    display: block;
}
h1 {
    padding: 20px;
    border-bottom: 4px solid #ccc;
    background-color: #353535;
    color: #fff;
}
.result {
    font-size: 50px;
    font-weight: bold;
    display: inline-block;
    padding: 30px;
    background: #cffecd;
}
</style>
<h1>PHP crawler</h1>
<form class="main_form" method="post">
	<label>URL:</label> <input name="url" type="text" value="<?=$url;?>"/><br/>
	<label>Search HTML:</label>
	<textarea name="search" type="textarea" value='<?=$search;?>'><?=$search;?></textarea>
	<input name="submit" type="submit" value="Submit"/>
</form>
<div class="result">
<?php
// -----------------------------------------------------------------------------
// test it!
if (isset ($_POST['submit']))
{
	echo $response = scraping_generic($_POST['url'], $_POST['search']);

}
?>
</div>
<h1>Examples: </h1>
<div class="example">
	<label>URL: https://www.klyd.de/de/pdpaola-kette-silber-m.html</label>
	<pre><?php echo htmlspecialchars('<div class="final-price inline-block" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                    <span class="price-label block">
                                            </span>
                <span id="product-price-1623" class="price-wrapper title-font font-medium text-2xl text-gray-900">
                    <span class="price" x-html="getFormattedFinalPrice()">59,00&nbsp;€</span>
                </span>
                <meta itemprop="price" content="59">
                <meta itemprop="priceCurrency" content="EUR">
            </div>'); ?></pre>
</div>

<div class="example">
	<label>URL: https://www.spielematerial.de/de/roleplay-3.html</label>
	<pre><?php echo htmlspecialchars('<span class="price-container price-final_price tax weee">
        <span id="product-price-23445" data-price-amount="8" data-price-type="finalPrice" class="price-wrapper "><span class="price">8,00&nbsp;€</span></span>
        </span>'); ?></pre>
</div>

<div class="example">
	<label>URL: https://en.zalando.de/canda-trenchcoat-beige-c6f22t07t-b11.html</label>
	<pre><?php echo htmlspecialchars('<p class="_0xLoFW u9KIT8 vSgP6A"><span class="sDq_FX _4sa1cA FxZV-M HlZ_Tf">99,99&nbsp;€</span><span class="sDq_FX lystZ1 FxZV-M Yb63TQ uMACAo">VAT included</span></p>'); ?></pre>
</div>

<div class="example">
	<label>URL: https://www.begadi.com/magazinboden-fur-army-armament-r17-kjw-kp-13-we-g-serie-gbb-magazine-aus-aluminium-lange-version-rot.html</label>
	<pre><?php echo htmlspecialchars('<div class="product-info-price"><div class="price-box price-final_price" data-role="priceBox" data-product-id="128344" data-price-box="product-id-128344">
	<span class="price-container price-final_price tax weee" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
			<span id="product-price-128344" data-price-amount="6.9" data-price-type="finalPrice" class="price-wrapper "><span class="price">6,90&nbsp;€</span></span>
					<meta itemprop="price" content="6.9">
			<meta itemprop="priceCurrency" content="EUR">
		</span>

	</div>
	<div class="product-additional-info">
		<span class="tax-details">
						<span class="tax-text">inkl. MwSt., zzgl. </span><a class="tax-link" href="/shipment-info" target="_blank">Versandkosten</a>
				</span>
		<div class="baseprice">
		   <span class="tax-text"></span>
		</div>
	</div>
	<span class="tax-saving">
	</span>
	<br>
	<span class="tax-saving">
	</span>

	</div>'); ?></pre>
</div>

