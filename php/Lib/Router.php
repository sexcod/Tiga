<?php
/**
 * Limp - less is more in PHP
 * @copyright   Bill Rocha - http://google.com/+BillRocha
 * @license     MIT
 * @author      Bill Rocha - prbr@ymail.com
 * @version     0.0.1
 * @package     Library\Neos
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

namespace Lib;

class Router 
{

    private $url = '';
    private $http = '';
    private $base = '';
    private $request = '';
    private $routers = [];
    private $params = [];
    private $all = [];
    private $method = 'GET';
    private $separator = '::';

    private $controller = '';
    private $action = '';
    private $defaultController = 'Resource\Main';
    private $defaultAction = 'pageNotFound';
    
    //namespace prefix for MVC systems - ex.: '\Controller'
    private $namespacePrefix = ''; 

    static $node = null;
    
    //GETs -----------------------------------------------------------------
    function getUrl() 
    {
        return $this->url;
    }

    function getHttp() 
    {
        return $this->http;
    }

    function getBase() 
    {
        return $this->base;
    }

    function getRequest() 
    {
        return $this->request;
    }

    function getRouters() 
    {
        return $this->routers;
    }

    function getAll() 
    {
        return $this->all;
    }

    function getMethod() 
    {
        return $this->method;
    }
    
    function getController() 
    {
        return $this->controller;
    }

    function getAction() 
    {
        return $this->action;
    }

    function getSeparator() 
    {
        return $this->separator;
    }

    function getParams()
    {
        return count($this->params) > 0 ? $this->params : null;
    }

    //SETs -----------------------------------------------------------------
    function setSeparator($v)
    {
        $this->separator = $v;
        return $this;
    }
    
    function setDefaultController($v)
    {
        $this->defaultController = trim( str_replace('/', '\\', $v), '\\/ ');
        return $this;
    }
    
    function setDefaultAction($v)
    {
        $this->defaultAction = trim($v, '\\/ ');
        return $this;
    }
    
    function setNamespacePrefix($v)
    {
        $this->namespacePrefix = $v === '' ? '' : '\\'.trim( str_replace('/', '\\', $v), '\\/ ');
        return $this;
    }
    
    /**
     * Constructor  
     */
    function __construct( 
        $request = null, 
        $url = null)
    {
        if ($request !== null)
            define('_RQST', $request);

        if ($url !== null)
            define('_URL', $url);        

        //Load configurations
        if(method_exists('Config\Neos\Router', 'routers'))
            (new \Config\Neos\Router)->routers($this);

        $this->method = $this->requestMethod();
        $this->mount();
    }

    /**
     * Singleton instance
     *
     */
    static function this()
    {
        if(is_object(static::$node)) return static::$node;
        //else...
        list($routers, $request, $url) = array_merge(func_get_args(), [null, null, null]);
        return static::$node = new static($routers, $request, $url);
    }

    /**
     * Make happen...
     *
     */
    function run($log = false)
    {
        $res = $this->resolve();

        $ctrl = isset($res['controller']) && $res['controller'] !== null ? $res['controller'] : $this->defaultController;
        $action = isset($res['action']) && $res['action'] !== null ? $res['action'] : $this->defaultAction;

        //Name format to Controller namespace
        $tmp = explode('\\', str_replace('/', '\\', $ctrl));
        $ctrl = $this->namespacePrefix;
        foreach($tmp as $tmp1){
            $ctrl .= '\\'.ucfirst($tmp1);
        }

        //save controller param
        $this->controller = $ctrl;
        $this->action = $action;

        //instantiate the controller
        $controller = new $ctrl(['params' => $res['params'], 'request' => $this->request]);

        $this->params = $res['params'];

        if (method_exists($controller, $action)){
            if(is_callable($log)) $log($this, __CLASS__); //call debuglog
            return $controller->$action();
        } else {
            $this->action = $this->defaultAction; //set Action
            if(is_callable($log)) $log($this, __CLASS__); //call debuglog
            return $controller->{$this->defaultAction}();
        }
    }

    /**
     * Resolve routers
     * 
     */
    function resolve() 
    {
        //first: serach in ALL
        $route = $this->searchRouter($this->all);

        //now: search for access method
        if ($route === false && isset($this->routers[$this->method]))
            $route = $this->searchRouter($this->routers[$this->method]);

        //not match...
        if ($route === false)
            $route['controller'] = $route['action'] = $route['params'] = $route['request'] = null;

        //set params
        $this->controller = $route['controller'];
        $this->action = $route['action'];
        $this->params = $route['params'];

        //out with decoded router OR all null
        return $route;
    }

    /**
     * Insert/config routers
     *
     */
    function respond(
        $method = 'all', 
        $request = '', 
        $controller = null, 
        $action = null) 
    {
        $method = strtoupper(trim($method));

        //Para sintaxe: CONTROLLER::ACTION
        if(strpos($controller, $this->separator) !== false){
            $a = explode($this->separator, $controller);
            $controller = isset($a[0]) ? $a[0] : null;
            $action = isset($a[1]) ? $a[1] : null;
        }

        if ($method == 'ALL')
            $this->all[] = ['request' => trim($request, '/'), 'controller' => $controller, 'action' => $action];
        else {
            foreach (explode('|', $method) as $mtd) {
                $this->routers[$mtd][] = ['request' => trim($request, '/'), 'controller' => $controller, 'action' => $action];
            }
        }
        return $this;
    }

    /**
     * Mount 
     */
    private function mount() 
    {
        //Detect SSL access
        if (!isset($_SERVER['SERVER_PORT']))
            $_SERVER['SERVER_PORT'] = 80;
        $http = (isset($_SERVER['HTTPS']) && ($_SERVER["HTTPS"] == "on" || $_SERVER["HTTPS"] == 1 || $_SERVER['SERVER_PORT'] == 443)) ? 'https://' : 'http://';

        //What's base??!
        $base = isset($_SERVER['PHAR_SCRIPT_NAME']) ? dirname($_SERVER['PHAR_SCRIPT_NAME']) : rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), ' /');

        if ($_SERVER['SERVER_PORT'] != 80  && $_SERVER['SERVER_PORT'] != 443)
            $base .= ':' . $_SERVER['SERVER_PORT'];

        //URL & REQST Constants:
        defined('_RQST') || define('_RQST', urldecode(isset($_SERVER['REQUEST_URI']) ? urldecode(trim(str_replace($base, '', trim($_SERVER['REQUEST_URI'])), ' /')) : ''));
        defined('_URL') || define('_URL', isset($_SERVER['SERVER_NAME']) ? $http . $_SERVER['SERVER_NAME'] . $base . '/' : '');

        $this->request = _RQST;
        $this->url = _URL;
        $this->base = $base;
        $this->http = $http;
    }

    /**
     * Search for valide router
     *
     * @params
     */
    private function searchRouter($routes) 
    {
        foreach ($routes as $route) {
            if($route['controller'] === null) continue;

            if (!preg_match_all('#^' . $route['request'] . '$#', $this->request, $matches, PREG_OFFSET_CAPTURE))
                continue;
            // retrabalhando matches
            $matches = array_slice($matches, 1);

            // parametros
            $params = array_map(function ($match, $index) use ($matches) {

                if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                    return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                } else {
                    return (isset($match[0][0]) ? trim($match[0][0], '/') : null);
                }
            }, $matches, array_keys($matches));

            $route['params'] = $params;
            return $route;
        }
        //não existe rotas
        return false;
    }

    /**
     * Get all request headers
     * @return array The request headers
     */
    private function requestHeaders() 
    {
        // getallheaders available, use that
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        // getallheaders not available: manually extract 'm
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * Get the request method used, taking overrides into account
     * @return string The Request method to handle
     */
    private function requestMethod() 
    {
        // Take the method as found in $_SERVER
        $method = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_start();
            $method = 'GET';
        } // If it's a POST request, check for a method override header
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->requestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }
        return $method;
    }
}
