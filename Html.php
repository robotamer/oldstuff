<?php

/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      TaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */


/**
 * @author     Dennis T Kaplan
 */

class Html {

	/**
	 * @var  array  preferred order of attributes
	 */
	public static $attribute_order = array
	(
		'action',
		'method',
		'type',
		'id',
		'name',
		'value',
		'href',
		'src',
		'width',
		'height',
		'cols',
		'rows',
		'size',
		'maxlength',
		'rel',
		'media',
		'accept-charset',
		'accept',
		'tabindex',
		'accesskey',
		'alt',
		'title',
		'class',
		'style',
		'selected',
		'checked',
		'readonly',
		'disabled',
	);

    public static function br($count=1, $var=FALSE) {
        $i = '<br />';
        $br='';
        while ($count > 0) {
            $br .= $i;
            $count--;
        }
        echo $var.$br;
    }
    public static function pre($array, $name = '') {
        echo '<br />'.$name.'<br />';
        echo '<pre>'.$array.'</pre>';
    }
    public static function Anchor($uri, $title = NULL, $attributes = NULL, $protocol = NULL, $escape_title = FALSE) {
        /**
         * Create HTML link anchors.
         *
         * @param   string  URL or URI string
         * @param   string  link text
         * @param   array   HTML anchor attributes
         * @param   string  non-default protocol, eg: https
         * @param   boolean option to escape the title that is output
         * @return  string
         */
        if($protocol === NULL) {
            $protocol = (getenv('HTTPS') == 'on') ? 'https' : 'http';
        }
        if (strpos($uri, '#') === 0) {
            // This is an id target link, not a URL
            $site_url = $uri;
        }elseif (strpos($uri, '://') === FALSE) {
            $site_url = Uri::baseUrl() . $uri ;
        }else {
            //$attributes['target'] = '_blank';
            $site_url = $uri;
        }
        return  '<a href="'.$site_url.'"'
                // Attributes empty? Use an empty string
                .(is_array($attributes) ? htmlAttributes($attributes) : '').'>'
                // Title empty? Use the parsed URL
                .($escape_title ? htmlspecialchars((($title === NULL) ? $site_url : $title), ENT_QUOTES, 'UTF-8', FALSE) : (($title === NULL) ? $site_url : $title)).'</a>';
    }
    public static function lst($list, $type = 'ul', $attributes = '', $depth = 0) {
        /**
         * Generates an html ordered or unordered list
         * from an single or multi-dimensional array.
         *
         * @access	private
         * @param	mixxed
         * @param	string Ordered List: ol or Unordered List: ul
         * @param	mixed
         * @param	intiger
         * @return	string
         */
        // If an array wasn't submitted there's nothing to do...
        if ( ! is_array($list)) return $list;

        // Set the indentation based on the depth
        $out = str_repeat(" ", $depth);

        // Were any attributes submitted?  If so generate a string
        if (is_array($attributes)) $attributes = self::attributes($attributes);

        // Write the opening list tag
        $out .= "<".$type.$attributes.">\n";

        // Cycle through the list elements.  If an array is
        // encountered we will recursively call _list()

        static $_last_list_item = '';
        foreach ($list as $key => $val) {
            $_last_list_item = $key;

            $out .= str_repeat(" ", $depth + 2);
            $out .= "<li>";

            if ( ! is_array($val)) {
                $out .= $val;
            }
            else {
                $out .= $_last_list_item."\n";
                $out .= htmlList($val, $type, '', $depth + 4);
                $out .= str_repeat(" ", $depth + 2);
            }

            $out .= "</li>\n";
        }

        // Set the indentation for the closing tag
        $out .= str_repeat(" ", $depth);

        // Write the closing list tag
        $out .= "</".$type.">\n";

        return $out;
    }
    public static function printa(&$array, $return = FALSE) {
        if ($return == FALSE) {
            echo "<pre>" . var_export($array, TRUE)."</pre><hr>";
        }else {
            return "<pre>" . var_export($array, TRUE)."</pre><hr>";
        }
    }
    public static function printn(&$array, $return = FALSE) {
        /**
         * printn
         *
         * Print Array
         *
         * @access       public
         * @param        array
         * @return       array name and output of array in pre as var_export
         */
        $caller_info = array_shift(debug_backtrace());
        $lines = file($caller_info['file']);
        $line = $lines[$caller_info['line'] - 1];
        if(preg_match('/printn\\s*\\(\$(\\w+)/', $line, $matches)) {
            $name_of_my_array = $matches[1];
        }
        $html = "<hr><h3>". $name_of_my_array . "<pre>";
        $html .= var_export($array, TRUE);
        $html .=  "</pre></h3><hr>";
        if ($return == FALSE) {
            echo $html;
        }else {
            return $html;
        }
    }
	/**
	 * Compiles an array of HTML attributes into an attribute string.
	 *
	 * @param   array   attribute list
	 * @return  string
	 */
	public static function attributes(array $attributes = NULL) {
		if(empty($attributes) || $attributes === NULL) return '';

		$sorted = array();
		foreach(html::$attribute_order as $key) {
			if(isset($attributes[$key])) {
				$sorted[$key] = $attributes[$key];
			}
		}

		// Add the once which are not in our list
		$attributes = $sorted + $attributes;

		$compiled = '';
		foreach($attributes as $key => $value) {
			if($value === NULL){
				// Skip attributes that have NULL values
				continue;
			}
			$compiled .= ' '.$key.'="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"';
		}
		return $compiled;
	}
    final private function __construct() {}
}
