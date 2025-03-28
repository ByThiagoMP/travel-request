# Pré-requisitos

Se você quiser evoluir ou personalizar o código do aplicativo, precisará de alguns componentes instalados no seu ambiente de desenvolvimento. Abaixo está a lista de requisitos:


* ```Instalar node, pnpm e VUE``` é necessário para o serviço de frontend.
* ```Instalar Docker``` é necessário para construir as imagens do contêiner e hospedar localmente.

💡 Caso queira rodar o projeto localmente sem Docker, será necessário configurar o arquivo .env.

Este é um serviço Frontend feito em [Node.js](https://nodejs.org/), [pnpm](https://pnpm.io/pt/) e [Vue.js](https://vuejs.org/)


## Rodando o Projeto sem Docker

Execute o aplicativo localmente:

1. **Instale as dependências**:
    - No terminal, navegue até a pasta do `Frontend` e execute:
    ```bash
    npm install
    ```
2. **Configure o arquivo `.env`**:
    - Copie o arquivo `.env.example` para `.env` e edite com as variáveis necessárias:
    ```bash
    VITE_APP_URL_BASE_SERVICES=a_sua_url_backend
    ```

3. **Inicia o servidor em modo desenvolvimento**:

    ```bash
    npm run dev
    ```

    ou 

    **Compila o projeto para produção**:

    ```bash
    npm run build
    ```

## Iniciando com Docker

1. **Suba os containers e o build**:

    ```bash
    docker-compose up --build
    ```
Após o comando, o docker será configurado pronto para usar o Backend

2. **Acesse a aplicação**:
    - Acesse pelo navegador no endereço no `http://127.0.0.1:8080/` (Local).