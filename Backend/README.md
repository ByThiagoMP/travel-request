# Pré-requisitos

Se você deseja evoluir ou personalizar o código do aplicativo, precisará de alguns componentes instalados no seu ambiente de desenvolvimento para construir e executar o projeto. Abaixo está a lista de requisitos:


* ```Instalar MySQL``` é necessário para o serviço de bando de dados.
* ```Instalar PHP 8+, Composer e Laravel 10+``` é necessário para o serviço de backend.
* ```Instalar Postman``` Utilizado para gerar a documentação e uma Collection pronta para uso da API.
* ```Instalar Docker``` é necessário para construir as imagens do contêiner e hospedar localmente.

💡 Caso queira rodar o projeto localmente sem Docker, será necessário configurar o arquivo .env.

Este é um serviço Backend feito em [PHP](https://www.php.net/) com [Composer](https://getcomposer.org/) e [Laravel](https://laravel.com/docs/10.x/validation), utilizando banco de dados [MySQL](https://www.mysql.com/).


## Rodando o Projeto sem Docker

Execute o aplicativo localmente:

1. **Instale as dependências**:
    - No terminal, navegue até a pasta do `Backend` e execute:
    ```bash
    composer install
    ```
2. **Configure o arquivo `.env`**:
    - Copie o arquivo `.env.example` para `.env` e edite com as variáveis necessárias:
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=seu_banco
    DB_USERNAME=usuario
    DB_PASSWORD=senha

    APP_URL=http://localhost:8000
    APP_ENV=local
    ```

3. **Configurando JWT no Laravel**:
    - Siga a [documentação oficial do JWT Auth](https://jwt-auth.readthedocs.io/en/develop/laravel-installation/) para configurá-lo corretamente.

4. **Gere a chave da aplicação**:
    ```bash
    php artisan key:generate
    ```

5. **Execute as migrações e seeders** :
    - Para esta criando as tabelas e populando com os dados.
    ```bash
    php artisan migrate --seed
    ```

6. **Inicie o servidor local**:
    ```bash
    php artisan serve
    ```

## Iniciando com Docker

1. **Suba os containers com build**:
    ```bash
    docker-compose up --build
    ```
    - Após o comando, o docker será configurado pronto para usar o Backend

2. **Acesse a aplicação**:
    - Acesse pelo postman ou qualquer ferramenta de testes no endereço no `http://127.0.0.1:8000/` (Local).


## Executando Testes com PHPUnit

### Testando Localmente

2. **Executar os testes**:
    ```bash
    php artisan test
    ```
    Ou diretamente com PHPUnit:
    ```bash
    vendor/bin/phpunit
    ```

### Testando com Docker

1. **Acesse o container do PHP**:
    ```bash
    docker-compose exec app bash
    ```

2. **Execute os testes dentro do container**:
    ```bash
    php artisan test
    ```
    Ou:
    ```bash
    vendor/bin/phpunit
    ```

Assim, você pode garantir que sua aplicação está funcionando corretamente tanto no ambiente local quanto no Docker.