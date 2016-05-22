[Home](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/README.md)
 | [Bootstrap](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/bootstrap.md)
 | [Router](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/router.md)


#Constantes
Por mais que a gente não queira, não é possivel escapar de algumas constantes padronizados!

O Tiga usa as seguintes constantes definidas no arquivo [**Config\App.php**](https://github.com/sexcod/Tiga/tree/master/php/Config/App.php):

<table>
<tr><th>Constante</th><th>Descrição</th></tr>
<tr><th align="left" width="140">_WWW</th><td width="730">Caminho dos arquivos públicos - o arquivo index.php e os demais recursos web (CSS, JS, etc) devem estar neste local;</td></tr>

<tr><th align="left">_APP</th><td>Localização da pasta contendo todo os arquivos de PHP (inclusive do Composer) e configurações da aplicação;</td></tr>

<tr><th align="left">_PHAR</th><td>Para aplicações "empacotadas" com <a href="http://php.net/manual/pt_BR/book.phar.php">PHAR</a>, esta constante indica o diretório raiz;</td></tr>

<tr><th align="left">_CONFIG</th><td>Caminho para as configuração da aplicação. Por padrão, esta pasta está localizada em _APP.'Config/';</td></tr>

<tr><th align="left">_HTML</th><td>Diretório base para os arquivos HTML - templates e demais arquivos - de acesso restrito e que serão usados para a produção do documento final (a ser enviado ao browser) em HTML.</td></tr>
</table>
.

O roteador padrão do Tiga ([Lib\Router](https://github.com/sexcod/Tiga/tree/master/php/Lib/Router.php)) define mais duas outras constantes que são muito imnportantes para a sua aplicação e, caso prefira usar outro router, deve ser definida manualmente. 

São elas:

<table>
<tr><th>Constante</th><th>Descrição</th></tr>
<tr><th align="left" width="140">_RQST</th><td width="730">Contém os parâmetros da URL de acesso;</td></tr>
<tr><th align="left">_URL</th><td>A URL base do site.</td></tr>
</table>

###Exemplificando

Tomamos como base o seguinte caminho para sua aplicação: <code>/var/www/myApp</code> e obtemos, então, os seguintes possíveis valores (default):

```
_WWW:       /var/www/myApp/
_APP:       /var/www/myApp/php/
_PHAR:      phar://var/www/myApp/php/
_CONFIG:    /var/www/myApp/php/Config/
_HTML:      /var/www/myApp/html/
```
<sup>***Note que a barra no final do caminho já está presente para facilitar a concatenação.***</sup>

Da mesma forma, considerando a URL <code>https://meu_site.com/blog/2016/11/23/titulo-da-materia</code> será obtido os seguintes valores nas constantes do roteador:

```
_URL:       https://meu_site.com/
_RQST:      blog/2016/11/23/titulo-da-materia
```
<sup>***Neste caso também a _URL virá sufixada com uma barra para facilitar a concatenação***</sup>

Estas constantes podem ser usadas por qualquer classe que esteja usando o Tiga como framework. Você pode usar um script como o o seguinte e definir em sua classe essas constantes para manter compatibilidade com outros sistemas.

```php
defined("_APP") || define("_APP", __DIR__.'/');

    //--- demais "defines" conforme a necessidade de seu script.
```

### Redefinindo Constantes

Caso necessite modificar o conteúdo de qualquer dessas constantes você poderá fazê-lo de duas formas:

* Pré-definindo os valores no front controller (index.php) ou
* Modificando diretamente o arquivo de configuração da aplicação.

O primeiro caso é mais recomendado para fazer alguma experiência onde os valores serão modificados por um tempo e voltarão ao seu valor configurado. Basta, então, definir novos valores usando a função **define** do PHP:

```php
define("_APP", dirname(__DIR__).'/php/');

    //--- não esqueça de adicionar uma barra no final. 
```

A segunda alternativa consiste em abrir o arquivo de configuração ([{ root }/php/Config/App.php](https://github.com/sexcod/Tiga/tree/master/php/Config/App.php)) e o roteador ([{ root }/php/Lib/Router.php](https://github.com/sexcod/Tiga/tree/master/php/Lib/Router.php)), procurar as linhas com as declarações "define" e alterar os seus valores conforme a necessidade do seu projeto. Lembre-se, porém, que no caso de **_URL** e **_RQST** estas são obtidas a partir dos dados do próprio ambiente e servidor web - tenha atenção a isso.

---
[Home](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/README.md)
 | [Bootstrap](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/bootstrap.md)
 | [Router](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/router.md)
