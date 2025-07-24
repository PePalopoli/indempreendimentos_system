# PaloSystem3
Sistema Criado por Pedro Palópoli Versão 3 - 2022


Palópoli Painel `(CRUD admin)`
===================

O que é CRUD admin ?
-----------------------------

- **O CRUD Admin**  é uma ferramenta para gerar um **backend completo a partir de um banco de dados MySql** onde você pode criar, ler, atualizar e excluir registros em um banco de dados.

- **O backend é gerado em segundos** sem arquivos de configuração, onde há um monte de *"mágica"* e é muito difícil de se adaptar às suas necessidades.

- **O código gerado é totalmente personalizável e extensível.**

O CRUD admin tem sido desenvolvido em cima do micro framework
[Silex](http://silex.sensiolabs.org).



Instalação
------------

Clone o repositório

    git clone https://github.com/PePalopoli/PaloSystem3.git

    cd generate-crud



Use o composer e o bower para instalar as dependência

    php composer.phar install

    composer update

    bower install



Configure a conexão com o banco de dados no arquivo **DoctrineServiceProvider.php** em `src/Provider/DoctrineServiceProvider.php` e no .htaccess em `web/.htaccess`

```php
    $app['dbs.options'] = array(
        'db' => array(
            'driver' => 'pdo_mysql',
            'dbname' => getenv('DATABASE_NAME') ?: 'develop',
            'host' => getenv('DATABASE_HOST') ?: '127.0.0.1',
            'user' => getenv('DATABASE_USER') ?: 'develop',
            'password' => getenv('DATABASE_PASS') ?: 'develop',
            'charset' => 'utf8',
        ),
    );
```

Caso queira usar apenas as configurações do doctrine remova essas linhas do .htaccess

```
<IfModule mod_env.c>
    SetEnv DATABASE_NAME develop
    SetEnv DATABASE_USER develop
    SetEnv DATABASE_PASS develop
    SetEnv DATABASE_HOST 127.0.0.1
</IfModule>
```

Agora, execute o seguinte comando que irá gerar o backend CRUD:

    php console generator

Inicie o servidor:

    php console serve

Abra em seu navegador:

    http://localhost:8000/admin

Adicionar um usuário para acessar, primeiro verifique se a tabela contém todas colunas necessárias:

    user:table

Depois crie o novo usuário:

    php user:create
teste

Helpers
------------

* [Node/Bower](http://www.codediesel.com/javascript/installing-bower-on-ubuntu-14-04-lts/) - Guia de instalação

Configurações
------------

* [Session](http://silex.sensiolabs.org/doc/providers/session.html) - O SessionServiceProvider oferece um serviço de armazenamento de dados persistente entre solicitações.

* [Form](http://silex.sensiolabs.org/doc/providers/form.html) - O FormServiceProvider fornece um serviço para construção de formulário utilizando componentes do Symfony2.

* [Translation](http://silex.sensiolabs.org/doc/providers/translation.html) - O TranslationServiceProvider fornece um serviço para traduzir o seu aplicativo em diferentes idiomas.

* [Validator](http://silex.sensiolabs.org/doc/providers/validator.html) - O ValidatorServiceProvider oferece um serviço de validação de dados. É mais útil quando usado com o FormServiceProvider, mas também pode ser usado independente.

* [URL Generator](http://silex.sensiolabs.org/doc/providers/url_generator.html) - O UrlGeneratorServiceProvider fornece um serviço para a geração de URLs para rotas nomeadas.

* [Doctrine](http://silex.sensiolabs.org/doc/providers/doctrine.html) - O DoctrineServiceProvider fornece integração com a Doutrina DBAL para acessar banco de dados fácil.

* [Swiftmailer](docs/SWIFTMAILER.md) - Configurações de envio de e-mail

* [Controladores como Serviços](http://silex.sensiolabs.org/doc/providers/service_controller.html) - Como seu aplicativo Silex cresce, você pode querer começar a organizar seus controladores de uma forma mais formal. Silex pode usar as classes do controlador fora da caixa, mas com um pouco de trabalho, os controladores podem ser criados como serviços, dando-lhe o pleno poder de injeção de dependência e carregamento lento.

* [Twig](http://twig.sensiolabs.org), [Twig Provider](http://silex.sensiolabs.org/doc/providers/twig.html), [Extensão](http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension) - O TwigServiceProvider fornece integração com o mecanismo de modelo Twig.
