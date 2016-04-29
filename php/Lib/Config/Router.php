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

class Router
{

    function routers(&$router)
    {
        //Routes:

        $router->respond('get',  '/',    'Resource\Main::pageNotFound');
    }
}








/* Samples:

        ->respond('get|post', 'blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', 'blog1') //blog/2015/11/23/titulo_da_materia
        ->respond('get|post', 'blog(/\d{4}(/\d{2}(/\d{2}(.*)?)?)?)?', 'blog2') //blog/2015/11/23/qualquer coisa aqui
        ->respond('get|post', '/user/(\d+)/(\w+)', 'usuarioIdNome', 'index') //user/00234/Nome do cara
        ->respond('get|post', '/user/(\d+)', 'usuarioId', 'index') //user/09005
        ->respond('get|post', '/user/(\d+)/(\w+)/(\w+)', 'usuarioIdNomeESobrenome', 'index') //user/231/Paulo/Rocha
        ->respond('get|post', '/user/(\d+)/(.*)', 'usuarioIdNomeCompleto', 'index')
        ->respond('get|post', '/user', 'user', 'index') //user
        ->respond('post', 'msg', 'msg')
        
        ->respond('post', 'sendmail/(.*[^<>()[\]\.,;:\s@\"])@(.*[^<>()[\]\.,;:\s@\"])\.(\w+)/(.*)', 'sendmail') //sendmail/prbr@ymail.com/23998  ((POST only)) call \Controller\Sendmail
        
        ->respond('post', 'upfile', 'upfile')
        
        ->respond('get|post', 'controller/(.*?)/(.*?)/(.*)', function($controller, $action, $params) {
            echo'Controller: ' . $controller . '<br>Action: ' . $action . '<br>Params: ' . $params;
            //after process, call controller/action
            return ['controller' => $controller, 'action' => $action, 'params' => 'none'];
        }) //controller/admin/index/values


        ->respond('get|post', 'blogs(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', function($ano, $mes, $dia, $titulo) {
            echo 'Ano: ' . $ano .
            '<br>Mês: ' . $mes .
            '<br>Dia: ' . $dia .
            '<br>Título: ' . $titulo;
            exit('<br>finish callback!');
            //In this case, the CALLBACK call the Controller/Action or stop...
        }); //Resolve with callback
*/        