[Home](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/README.md)
 | [Instalação](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/instalacao.md)
 
# A Proposta do Tiga

Definitivamente não é possível construir uma estrutura que vai funcionar para **todo** e qualquer projeto web com PHP.

Fato é que frameworks gigantes como o Zend, Laravel, CodeIgniter, mesmo pagando um alto preço (lentos, consumidores de recursos, complicados ...) ainda assim não resolvem **todos** os casos - ainda vão precisar de recursos externos, tanto de bibliotecas de terceiros, quanto criadas pelo próprio desenvolvedor.

*A proposta do __TIGA__ é __não ser um sistema completo__!*

Você terá uma **base** ou um "quadro de trabalho" bem simples e fácil de implementar, sem necessidade de investir muito tempo lendo manuais. 
Em fim, o básico: **Um "starter" para seu projeto web em PHP**.

## Como Funciona

Existem duas coisas básicas e importantíssimas em um aplicação web com PHP: A organização da **estrutura** (namespaces) e o **roteamento** (encaminhamento) das requisições.

Provavelmente você estará pensando:

-- Hei!! Tem outras coisas importantes também, como o banco de dados, a template engine...

*E você está certo!!*

Sempre foi **vendida** pra você uma configuração de frameworks empacotados com essas "coisas" todas, porém, que você não irá usar - pelo menos em pequenos projetos.

Basta pensar um pouco: seu site corporativo ou um portifólio (sites bem simples) realmente precisarão de banco de dados? Um template engine é mesmo necessário?
Mas você, mesmo que responda negativamente, vai ter que levar o **pacote completo**!

**O TIGA nunca será assim!**

### Estrutura
Veja a figura abaixo para entender a estrutura de pastas da proposta, digamos, default:

![estrutura - figura 1](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/assets/f1.png) 

A pasta **php** contém todos os recursos (scripts, libs, etc) da sua aplicação PHP.

O acesso a esses recursos se dá pelo **namespace**, configurado no Composer que usará seu sistema de **autoload** para carregá-los.

Veja na figura abaixo o conteúdo (básico) da pasta **php**:

![estrutura da pasta php - figura 2](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/assets/f2.png)

Todos os **namespaces** nesta pasta usarão o "vendor name" **App**. Isso porque o Composer exige que um **vendor name** registrado no Packagist (packagist.com) tem que ter pelo menos *quatro caracteres*, então, para que não haja conflitos com pacotes instalados, este namespace "local", da aplicação, foi definido assim.

A pasta "php/Config", cujo o namespace é **App\Config**, contém os arquivos de configuração para os recursos da aplicação (seus controladores, libs, etc) assim como das bibliotecas instaladas pelo Composer.

Por exemplo, a configuração do Twig, cujo vendor é "Twig" e a classe principal "Twig\Twig", será acessada com o namespace **App\Config\Twig\Twig**. O Composer, então, fará automaticamente a inclusão do arquivo **php/Config/Twig/Twig.php**.

O arquivo pirncipal, que carrega a configuração básica do ambiente Tiga, está no caminho **php/Config/App.php**.

Ainda temos a pasta **php/Lib**, para conter os recursos comuns da aplicação (bibliotecas criadas pelo desenvolvedor de uso comum - com código reutilizável) e a pasta **php/Composer** contendo o Composer e, consequentemente, os recursos externos (vendor) instalados.

### Controllers

Não existe uma estrutura MVC forçada pelo Tiga!

Na verdade, devido a flexibilidade do sistema, qualquer padrão poderá ser usado: MVC, HMVC, Modular, entre outros. 
Ou a mistura de todos no mesmo sistema.

Isso ocorre por que, usando o Composer como gerenciador de namespace (PSR 4) qualquer recurso pode ser chamado pelo router para controlar uma requisição. 
Sendo assim, você pode criar uma pasta e nomeá-la de **"Controller"** e criar seus controladores (tipo MVC) dentro.
Ou, se preferir, pode criar uma pasta para cada controlador da requisição de forma **modular**, podendo a pasta em questão conter não somente o controlador, mas também os models, componentes, bibliotecas e até mesmo recursos HTML.

Neste último caso (modular), por exemplo, é possível ver claramente a flexibilidade e reaproveitamento de código de forma bem clara. 
Um módulo pode ser copiado (a pasta inteira) e usado em outra aplicação sem muito o que modificar. 
Como o caminho (namespace) da configuração é o mesmo, ou seja **App\Config\App\Nome_da_pasta_do_modulo**, esse provavelmente será o único arquivo a modificar.
Com a mesma facilidade é possivel implementar outras configurações (HMVC, por exemplo) sem dificuldade e de total controle do desenvolvedor.

## Roteador
Aqui acontece uma das "magias" que tornam o Tiga um verdadeiro **smart framework** (**SFW**)!!

------- TODO: criar texto...

---
[Home](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/README.md)
 | [Instalação](https://github.com/sexcod/Tiga/tree/master/php/Lib/Doc/instalacao.md)
