<?php
/**
 * Localize
 *
 * @package		RoboTaMeR
 * @author		Dennis T Kaplan
 * @copyright	Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license		http://php.RoboTaMeR.com/license.html
 * @link		http://php.RoboTamer.com
 *
 *
 **/
/*
session_start();
$l = new Localize();
$l->setCurrency('TRY');
echo $l->getLocale('DE')."\n";
echo $l->getCurrency('TR')."\n";
echo $l->formatCurrency(5.66,'de_DE')."\n";
echo $l->formatCurrency(5.66,'tr_TR','EUR')."\n";
echo $l->getXFormated(75558.5)."\n";
//print_r(Localize::parseLocalesFile());
*/
/**
 * @category     RoboTaMeR
 * @package      Localize
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://RoboTamer.com/license.html
 * @link         http://RoboTamer.com
 */
class Localize
{
	const available_locales_ini = "Localize.ini";

	protected $cfg = array('US' => 'en_US.UTF-8');

	protected $country = 'US';

	function __construct(){
		$this->cfg = self::parseLocalesFile();
		$this->country = $this->setCountry();
	}

	function getCountry(){
		return $this->country;
	}

	function setCountry() {
		$country=NULL;
		if(isset($_COOKIE['Country'])){
			$country = gpcClean($_COOKIE['Country']);
			$country = strtoupper($country);
			if( ! array_key_exists($country, $this->cfg['xLocales'])) $country=NULL;
		}
		if(empty($country)) $country = 'US';
		return $country;
	}

	function getLocale($country = NULL){
		if($country === NULL) $country = $this->country;
		return strstr($this->cfg['xLocales'][$country],'.',TRUE);
	}
	function getXLocale($country = NULL){
		if($country === NULL) $country = $this->country;
		return $this->cfg['xLocales'][$country];
	}
	function getLanguage($country = NULL){
		if($country === NULL) $country = $this->country;
		return strstr($this->cfg['xLocales'][$country],'_',TRUE);
	}

	function getCurrency(){
		if(!isset($this->currency)) $this->setCurrency();
		return $this->currency;
	}

	function setCurrency($code = NULL){
		$currency = 'USD';
		if($code !== NULL){
			if(in_array($code,$this->cfg['currencies'])) $currency = $code;
		}else{
			if(isset($_COOKIE['Currency'])){
				$currency=gpcClean($_COOKIE['Currency']);
				if( ! in_array($currency, $this->cfg['currencies'])){
					$currency = 'USD';
				}
			}			
		}
		$this->currency = $currency;
	}

	public function getCurrCode($locale = NULL) {
		if($locale === NULL) $locale = $this->locale;
		if (extension_loaded('intl')) {
			$formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
			return $formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);
		}
	}
	function getXFormated($price){
		$price = $this->getExchange($price);
		return $this->formatCurrency($price);
	}

	function formatCurrency($price, $locale = NULL, $currency = NULL){
		if($locale === NULL) $locale = $this->getLocale();
		if($currency === NULL) $currency = $this->getCurrency();

		if (extension_loaded('intl')) {
			$fmt = new NumberFormatter( $locale, NumberFormatter::CURRENCY );
			return $fmt->formatCurrency($price, $currency);
		}else{
			$country = array_flip($this->cfg['currencies']);
			$country = $country[$currency];
			$xlocale = getXLocale($country);
			setlocale(LC_MONETARY, $xlocale);
			return money_format('%n',$price);
		}
	}

	/**
	 * Convert locale price to some other currency
	 * 
	 * @param $price float Price in locale currency
	 * @param $to string Currency to convert to
	 * @return float
	 **/
	function getExchange($price, $to = NULL){
		if($to === NULL) $to = $this->getCurrency();
		if(isset($_SESSION['xRate'])){
			$rate = $_SESSION['xRate'][$to];
		}elseif(defined('ETC') && is_file(ETC.'xRate.php')){ 
			$xRate = include(ETC.'xRate.php');
			if(isset($_SESSION)) $_SESSION['xRate'] = $xRate;
			$rate = $xRate[$to];
		}else{
			$rate = self::getExchangeRate($to);
			$rate = $rate[0];
		}
		return $price * $rate;
	}

	static function getExchangeRate($to,$from='USD') {
		$result = FALSE;
		if($to == $from) return array(0=>1);
		$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s='.$from.$to.'=X';
		$handle = fopen($url, 'r');
		if ($handle) {
			$result = fgetcsv($handle);
			fclose($handle);
			$result[0]=(FLOAT)$result[0];
			$d = explode('/',$result[1]);print_r($d);
			$result[1] = $d[2].'-'.$d[0].'-'.$d[1];
		}
		return $result;
	}

	static function parseLocalesFile()
	{
		return parse_ini_file(__dir__.DIRECTORY_SEPARATOR.self::available_locales_ini,TRUE);
	}
}


?>