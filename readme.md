# Agrupamento de Voos

- [Descrição](#descrição)
  - [Regras](#regras)
  - [Atenção](#atenção)
- [Instalação](#instalação)
  - [Clonar projeto](#clonar-projeto)
  - [Instalar dependências](#instalar-dependências)
  - [Iniciar servidor](#iniciar-servidor)
- [Rotas](#rotas)
  - [Requisição](#requisição)
  - [Retorno](#retorno)
- [Postman](#postman)

## Descrição

A API Agrupamento de Voos realiza uma consulta, em uma API externa, que retorna diversos voos e faz o agrupamento dos mesmos. É retornado um JSON com o resultado ordenado pelo menor preço do grupo.

#### Regras

O grupo pode ser formado por vários voos de ida e de volta respeitando as seguintes regras:

- O grupo deve ter pelo menos uma opção de ida e de volta.
- A tarifa de todos os voos do grupo deve ser a mesma.
- O valor total do grupo não pode sofrer alterações com as combinações dos valores dos voos de ida e de volta.

#### Atenção

Para fazer as combinações, a API leva em consideração apenas a tarifa e os valores dos voos. Não foi levado em consideração a origem e o destino de cada voo, assim como os horários de partida e chegada.

## Instalação

Trata-se de uma API REST desenvolvida utilizando Laravel 5.8 e PHP 7.4.11. Portanto, para utilizar a API é necessário ter o [PHP](https://www.php.net/downloads.php) e o [Composer](https://getcomposer.org/download/) instalados.

#### Clonar projeto

Com o ambiente preparado, clone o projeto utilizando o comando:
```
git clone https://github.com/danilodvaz/Flight-Grouping.git
```

#### Instalar dependências

Depois de clonado, acesse o diretório raiz do projeto e instale as dependências utilizando o gerenciador de dependências Composer:
```
composer install
```

#### Iniciar servidor

Após o termino da instalação das dependências, ainda no diretório raiz, execute o seguinte comando para iniciar o servidor:
```
php artisan serve
```

## Rotas

A API disponibiliza a rota **flightGrouping**, que realiza a consulta dos voos e retorna o JSON com os resultados.

#### Requisição

Para consumir o recurso da API, realize uma requisição **GET** para o seguinte endereço:
```
http://127.0.0.1:8000/api/flightGrouping
```

#### Retorno

Após realizar a requisição, a API irá consultar e processar os voos, retornando uma resposta em formato JSON com a seguinte estrutura:

```json
{
    "flights": "Array de objetos com todos os voos que foram retornados na cosulta da API",
    "groups": [
        {
            "uniqueId": "Identificador único do grupo",
            "totalPrice": "Valor das combinações de voos de ida e de volta do grupo",
            "outbound": [
                {
                    "id": "Identificador do voo de ida"
                }
            ],
            "inbound": [
                {
                    "id": "Identificador do voo de volta"
                }
            ]
        }
    ],
    "totalGroups": "Número total de grupos gerados",
    "totalFlights": "Número total de voos que realmente foram utilizados para montar os grupos",
    "cheapestPrice": "Valor do grupo mais barato",
    "cheapestGroup": "Identificador único do grupo com o valor mais barato"
}
```

Descrição detalhada da estrutura de retorno:

| Campo | Tipo | Descrição |
| --- | :---: | --- |
| flights | "Array" | "Array de objetos com todos os voos que foram retornados na cosulta da API" |
| groups | "Array" | "Array de objetos com os grupos formados" |
| groups->uniqueId | "Integer" | "Identificador único do grupo" |
| groups->totalPrice | "Float" | "Valor das combinações de voos de ida e de volta do grupo" |
| groups->outbound | "Array" | "Array de objetos com os identificadores dos voos de ida" |
| groups->outbound->id | "Integer" | "Identificador do voo de ida" |
| groups->inbound | "Array" | "Array de objetos com os identificadores dos voos de volta" |
| groups->inbound->id | "Integer" | "Identificador do voo de volta" |
| totalGroups | "Integer" | "Número total de grupos gerados" |
| totalFlights | "Integer" | "Número total de voos que realmente foram utilizados para montar os grupos" |
| cheapestPrice | "Float" | "Valor do grupo mais barato" |
| cheapestGroup | "Integer" | "Identificador único do grupo com o valor mais barato" |

## Postman

A documentação também pode ser encontrada no Postman acessando o [link](https://documenter.getpostman.com/view/13333287/TVYM5GDY#agrupamento-de-voos).
