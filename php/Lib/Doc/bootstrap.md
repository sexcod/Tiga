[<< Instalação](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/instalacao.md)
 | [Home](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/README.md)
 | [Constantes >>](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/constantes.md)
# Front Controller

O arquivo **index.php** é o front controller do TIGA. Localizado no root da aplicação (servidor web) é o ponto de entrada para toda a requisição feita ao servidor que não seja para um recurso (caminho/arquivo) real.
Em um servidor com o Apache, o arquivo **.htaccess** é o resposnsável por configurar o módulo **rewrite** que "direciona" todas as requisições para o arquivo **index.php**. 

```
1  RewriteEngine On
2  RewriteCond %{REQUEST_FILENAME} !-d
3  RewriteCond %{REQUEST_FILENAME} !-f
4  RewriteRule . index.php [L]
```
Configurações semelhantes podem ser feitas em servidores NGINX ou Windows IIS (entre outros).

Este é código mais simples para iniciar uma aplicação com o **TIGA**, a partir do front controller *(index.php)*: 

```php
1  <?php
2  
3  // include Application Config
4  include 'php/Config/App.php';
5  
6  // Running the application
7  (new Lib\Router)->run();
```  

Como pode ser visto acima, duas coisas são necessárias para a iniciação de uma aplicação PHP: **as configurações e o roteador**.

O primeiro arquivo contém as configurações mínimas que serão usadas ao longo de toda a "vida" dessa aplicação. Demais configurações serão carregadas conforme a demanda,no decorrer do trabalho realizado por essa requisição.

Em seguida é necessário saber que tipo de recurso foi solicitado. 

Uma aplicação web (por exemplo) executa uma série de procedimentos e processamentos que, em conjunto, configuram um web site completo. Normalmente essas requisições são originadas por um usuário através de um navegador web, mas, também podem ter as mais variadas ferramentas e canais de acesso. A aplicação PHP deve estar preparada para identificar de forma inteligente essa requisição e convocar corretamente o objeto responsável pela execução dos processos necessários para entregar o produto dessa requisição.

Nesse projeto, o gerenciamento das requisições se dá pela classe **Router** *(Lib/Router)* com seu arquivo de configuração situado em *\Config\Neos\Router*. Neste arquivo de configuração são definidas as rotas para os **controladores de processos**, com base no tipo e dados da requisição. Assim, apenas editando essa configuração, a aplicação pode gerenciar desde requisições de um navegador web convencional até formatos de acesso como de APIs (restfull).

A aparência desse arquivo de configuração pode ser vista aqui:

```php
1  namespace Config\Neos;
2  class Router
3  {
4      function routers(&$router)
5      {
6          //Routes:
7          $router->respond('get',      '/',       'Site\Page::index')
8                 ->respond('post',     '/login',  'User\Access::login')
9                 ->respond('get|post', '/logout/(*.)', 'User\Access', 'logout')
10                ->respond('all',      '/license',     function()
11                                                      { 
12                                                          exit(_HTML.'license.html');
13                                                      }
14                         );
15      }
16 }
```  

O objeto *Config\Neos\Router* é o padrão nesse sitema, porém, outros routers (roteadores) podem ser usados ou combinados para adicionar ainda mais funcionalidades ao sistema.

###Como funciona

Na linha **1** temos a definição do namespace desse objeto.

A função **routers** deve receber um objeto Router como argumento passado popr referencia. Esse objeto é o próprio Router em si, que no caso da configuração default é **Lib\Router**. A própria classe Lib\Router está configurada para buscar esse arquivo de configuração automaticamente, através do **namespace** apropriado.

Na linha **7** (em diante) temos uma chamada ao método **respond** que tem a responsabilidade de informar as rotas definidas pelo desenvolvedor da aplicação, apresentado a seguinte sintaxe:

**Argumentos**

1. Tipo de acesso: deve ser separado por um **|** quando houver mais de um tipo. Os tipos reconhecidos até essa versão são: <code>get|post|delete|put|patch|all</code>. A string pode ser com letras maiúsculas ou minúscula.
2. Requisição por URL amigável: Você também pode definir uma **url simples** ou uma **expressão regular** que capture ou defina uma determinada url de requisição.
3. Alvo ou Controlador da requisição. Devido a grande flexibilidade do Router, esse argumento (parâmetro) pode ser passado em tres formatos: 
    a. Uma string indicando um **namespace**, um separador configurável (default "::") e o **método** responsável (linhas 7 e 8);
    b. Um parametro **namespace** para uma classe, seguido por outro parâmetro (ambos string) que indica o nome do **método** de controle da requisição dessa classe (linha 9);
    c. Uma **função anônima** que pode receber os parâmetros da expressão regular (se usada) no segundo argumento, além dos recursos disponíveis no sistema (linhas de 10 a 14).

Apesar de conceitualmente o sistema funcionar como um **MVC**, este "controller" pode ser qualquer objeto instalado dentro da pasta *"root/php/"*, bem como **qualquer namespace válido**. Isso confere uma extraordinária flexibilidade ao sistema que pode ter em uma mesma aplicação o MVC, Rest, HMVC, Modular, etc. O desenvolvedor tem **toda** liberdade para usar o que mais adequado for a cada objeto/requisição de sua aplicação. 

A título de exemplo, apenas, vamos considerar um blog com acesso ao estilo Wordpress:

```php
1  $router->respond('get|post', 'blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', 'Blog\Article::show');
```
Esse exemplo captura uma url como **www.seu_site.com/blog/2016/11/23/titulo_da_materia**, acessado tanto por uma requisição GET quanto POST, carregando um módulo de **Blog** no caminho *{root}/app/Blog/Article.php* e chamando o método **show** dessa classe. Os parâmetros passados pela url podem ser capturados no método "show" usando o seguinte código:

```php
1  namespace Blog;
2  use Resource\Main;
3
4  class Article extends Main
5  {

 ---

15      public function show()
16      {
17          var_dump($this->params);
 ---

```
Usamos um objeto auxiliar **Resource\Main** com métodos e parâmetros pré-configurados, que extendido, facilita a utilização de recursos básicos do sistema. O próprio desenvolvedor pode inserir seus "resources" nesse caminho.
O parâmetro *"params"* da classe extendida é acessado na linha **17** desse exemplo. Se trata de um array com os dados recuperados da expressão regular configurada no código anterior. 




[Top](bootstrap.md)
