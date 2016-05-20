[Home](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/README.md)
 | [Bootstrap >>](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/bootstrap.md)


# Instalação

Se você pretende colaborar com o projeto (please) use o GIT para clonar o repositório com o seguinte comando:

    git clone https://github.com/sexcod/Tiga.git  
    Composer update
    
### Projeto
Para instalar o TIGA em um novo projeto nada mais simples e fácil que usar o *Composer* para isso: 

    Composer create-project neos/tiga 

O Composer vai criar uma pasta a partir do local onde você digitar esse comando com o nome "tiga", contendo os arquivos e pastas necessários. No final, o [script](https://github.com/sexcod/Tiga/tree/master/php/tiga) de configuração do Tiga será chamado e fará os ajustes necessários para seu sistema funcionar da melhor forma possível.

Opcionalmente você pode indicar o caminho para o Composer criar seu projeto:

    Composer create-project neos/tiga /caminho/de/seu/projeto
    ou ...
    Composer create-project neos/tiga ./

### Testando a instalação
Para um teste rápido da instalação você pode acessar diretamente a url do seu projeto (seu servidor web) ou usar o *builtin server* do PHP para isso:

    php -S localhost:80 

Em seguida, digite em seu navegador <code>http://localhost</code> e será mostrada a página inicial de sua aplicação.

### Requerimentos:
Você precisa confirmar se tem os seguintes recursos instalados em seu sistema para rodar corretamente um projeto com o TIGA:

- [x] [PHP 5.5.+](http://www.php.net) ( se possível use a **versão 7+** )  
- [x] [PDO](http://php.net/manual/pt_BR/book.pdo.php) e driver(s) para o banco de dados utilizado em sua aplicação
- [x] [Composer](https://getcomposer.org/)      
- [x] [OpenSSL](http://php.net/manual/pt_BR/openssl.installation.php)

Outros recursos deverão ser instalados (disponibilizados) conforme a demanda de sua aplicação.

