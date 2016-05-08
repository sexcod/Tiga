# Debug



**Rascunhos iniciais para classe Lib\Debug**

Exemplo de como configurar a geração de **logs** para o Router com a classe **Debug**

## Front Controller 
O arquivo **index.php** é o front controller do TIGA. Localizado no root da aplicação (servidor web) é o ponto de entrada para toda a requisição feita ao servidor que não seja para um recurso (caminho/arquivo) real.
Em um servidor com o Apache, o arquivo **.htaccess** é o resposnsável por configurar o módulo **rewrite** que "direciona" todas as requisições para o arquivo **index.php**. 

Esta é a forma mais simples de iniciar uma aplicação com o **TIGA**, a partir do front controller *(index.php)*: 

```php
<?php
  
// include Application Config
include 'php/Config/App.php';
  
// Running the application
(new Lib\Router)->run();
```  

### Função Log        
O caminho padrão para os arquivos de log é **/php/Logs/NameSpace/ClassName.php**. Os arquivos têm um tamanho preconfigurado e, depois que seu tamanho supera esse limite este é renomeado - recebendo a extenção ".bkp" - e um novo arquivo de log passa a ser escrito com o nome original.

Para exemplificar, usando a classe **\Lib\Router**, o arquivo de log é gravado em: *"root/php/Logs/Lib/Router.log"*.    
Os arquivos de backup (quando o tamanho do log atinge o limite) ficariam parecidos com: *"root/php/Logs/Lib/Router.log_20160510453123.bkp"*.

Uma função bem simples como a listada abaixo também pode ser chamada para a gravação dos logs de acesso do **Router**:

```php
<?php
/*    
  Exemplo 1   
  Usando uma função para gravar os arquivos de log    
*/   
  
// include Application Config
include 'php/Config/App.php';

// Running the application with (function) log
(new Lib\Router)->run( function($routerObject, $namespace){ logRouter($routerObject, $namespace); } );

// ------------------- Function --------------------------------
function logRouter    
(   
	$router,    
	$name = 'router',     
	$size = 10000   
){    
  //Configs
  $dir = _APP.'Logs/';
  $file = $dir.str_replace('\\', '_', $name).'.log';
  $size = 1000; // 1000 = 1k
  $type = FILE_APPEND;

  $debug = '['.date('Y/m/d H:i:s').'] '
		 .$router->getMethod().' | '
		 .$router->getController().$router->getSeparator().$router->getAction().' | '
		 .$router->getUrl().$router->getRequest();
  foreach($router->getParams() as $p){
	  $debug .= "\n\t+ ".(is_array($p) || is_object($p) ? print_r($p, true) : $p);
  }
  $debug .= "\n";
  //Verificando o caminho
  if(!is_dir($dir) || !is_writable($dir)){
	  mkdir($dir, 0777);
	  chmod($dir, 0777);
  }
	  //Verificando o tamanho do arquivo de log - limitando
  if(file_exists($file) && filesize($file) > $size){
	  rename($file, $file.'_'.date('YdmHis').'_'.uniqid(rand(0,10)).'.bkp');
	  $type = null;
  }

  //gravando o arquivo de log
  file_put_contents($file, $debug, $type);
}   
```   

### Class Lib\Debug   
Nesse modo, a classe Router chamará o Debug toda a vez que um acesso ao sistema for feito e essa última pegará os dados e gravará no arquivo de log prédefinido.

```php
<?php
/*    
  Exemplo 2   
  Usando a classe Lib\Debug para gravar os arquivos de log    
*/  
  
// include Application Config
include 'php/Config/App.php';

// Running the application with (object) log
(new Lib\Router)->run( function($routerObject, $namespace) {
                          (new Lib\Debug($routerObject, $namespace))->log(); 
                      });    
``` 
