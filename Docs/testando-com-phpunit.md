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