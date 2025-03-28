# Estrutura dos dados

O Pedidos de Viagem tem uma estrutura de dados muito simples e leve para dar suporte às informações primárias para a solução.

## Pedido de viagem banco de dados relacional

Usei [MySQL](https://www.mysql.com/) para dados transacionais. Ele gerencia os dados transacionais dos Pedido em tabelas, garantindo a integridade, consistência e durabilidade dos dados durante as operações. Isso facilita a manipulação de dados rápida, confiável e segura, aumentando a precisão e a confiabilidade das informações armazenadas, ao mesmo tempo em que fornece a escalabilidade necessária para o aplicativo.

O Diagrama Entidade-Relacionamento (DER) do Pedidos de Viagem para tabelas principais pode ser visto abaixo.

![AIRA DER](img/bd.JPG)

As tabelas mais importantes que você deve conhecer e que o sistema implica estão listadas abaixo.

* **usuários**: Armazena dados de usuários na plataforma. Como você pode ver, ele interage com a tabela principal  dentro do sistema. Como você pode ver abaixo, os usuários podem assumir várias funções dentro do aplicativo.

* **user_permissions**: Armazena dados de funções de usuários. Um usuário pode assumir qualquer função, temos atualmente user e admin.

* **travel_orders**: Armazena dados dos pedidos de viagem. Pode ser a data de partida, data de retorno e o destino.

* **travel_oders_status**: Armazena os status do pedido de viagem que atualmente são aprovado, cancelado e solicitado.

* **notifications**: Controla as notificações feito pelo usuário na hora de criar pedido, editar ou o fluxo de aprovação.