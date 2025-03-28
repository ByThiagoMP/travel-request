![Logo Onfly](Frontend/public/onfly-logo2.png)

# Gestão de Pedidos Viagem 

Este projeto foi desenvolvido como parte de um **teste técnico** para a [Onfly](https://www.onfly.com.br/), com o objetivo de criar um sistema simples e funcional de gerenciamento de pedidos de viagem.

O sistema permite que os usuários cadastrem pedidos de viagem, que passam por um fluxo de aprovação, no qual administradores podem aprovar ou cancelar as solicitações. Foi desenvolvido com foco em código limpo, arquitetura bem estruturada e boas práticas.

![Tela de Login](docs/img/tela-login.jpg)

> Este projeto foi desenvolvido como parte de um teste técnico para [Onfly](https://www.onfly.com.br/). A logo utilizada na interface é de propriedade da empresa e foi usada apenas para fins de demonstração visual.

## Visão Geral

Este repositório contém dois principais diretórios:

- `Backend/` - Aplicação desenvolvida em PHP com Laravel
- `Frontend/` - Aplicação desenvolvida com Vue.js PNPM

## Documentação

* [Estrutura de dados](./docs/estrutura-de-dados.md)
* [Iniciando o Backend](./docs/iniciando-backend.md)
* [Iniciando o Frontend](./docs/iniciando-front.md)
* [Testando com PHPUnit](./docs/testando-com-phpunit.md)

---

## Tecnologias Utilizadas

**Backend:**
- MySQL
- PHP 8+
- PHPUNIT
- Laravel 10+
- Postman
- JWT Auth
- Docker

**Frontend:**
- Node.js
- PNPM
- Vue.js 3
- Vite
- Docker

## Postman
Uma collection do Postman está disponível para facilitar o teste das rotas da API.
Download da Collection: [Collection Postman - Pedidos de Viagem](https://www.postman.com/restless-escape-823255/workspace/onfly/request/27431455-e7a7f2cb-b0d3-46b1-a98b-f689a669ee33?action=share&creator=27431455&ctx=documentation)

Para utilizá-la:

Exporte o arquivo .json do Postman e Importe;

Altere a variável {{base_url}} se necessário (ex: http://localhost:8000/api);

Use o endpoint de login para gerar o token e autorize as demais requisições com o JWT.

Para esta testando pelo Postman ou pela Web, deixei dois Seeders de usuários, um com a permissão de Admin e outro com User

E-mail: admin@example.com
Senha: 123456A@

E-mail: user@example.com
Senha: 123456A@

## Funcionalidades

- Cadastro de pedidos de viagem com data de ida, volta e destino
- Notificações de ações importantes (criação, edição, aprovação)
- Fluxo de aprovação por administradores (Aprovar ou Cancelar)
- Autenticação com JWT
- Painel administrativo com listagem de pedidos

---

## Observações

Este projeto é um teste técnico e não reflete um sistema concluído nem um design finalizado. Este não é um produto da Onfly oficialmente suportado.

---

© 2025 - Desenvolvido por ThiagoMP. Este projeto foi criado exclusivamente para fins de avaliação técnica.

Desenvolvido com carinho para o teste técnico da Onfly 💙

