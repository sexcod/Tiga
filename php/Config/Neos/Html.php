<?php
/**
 * NEOS PHP FRAMEWORK
 * @copyright   Bill Rocha - http://google.com/+BillRocha
 * @license     MIT
 * @author      Bill Rocha - prbr@ymail.com
 * @version     0.0.1
 * @package     Config\Neos
 * @access      public
 * @since       0.3.0
 *
 * The MIT License
 *
 * Copyright 2015 http://google.com/+BillRocha.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Config\Neos;

class Html
{
	private $name =             'default';
    private $cached =           false;
    private $mode =             'dev'; //pro|dev

    private $pathHtml =         '';
    private $pathHtmlCache =    '';
    private $pathWww =			'';
    private $pathStyle =        '';
    private $pathScript =       '';

    private $header =           null;
    private $footer =           null;

    private $forceCompress =    false;
    private $tag =              'x:';


    /**
     * Boot settings
     */
    function __construct()
    {
    	defined('_HTML') && $this->pathHtml = _HTML;
    	defined('_WWW')  && $this->pathWww =  _WWW;

        $this->pathHtmlCache = $this->pathHtml.'cache/';
        $this->pathStyle = $this->pathWww.'css/';
        $this->pathScript = $this->pathWww.'js/';

        $this->header = $this->pathHtml.'header.html';
        $this->footer = $this->pathHtml.'footer.html';
    }

    /*
     * Return all parameters
     */
	public function getParams()
	{
		foreach($this as $k=>$v){
			$cfg[$k] = $v;
		}
		return $cfg;
	}

}