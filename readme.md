## Agrupamento de Voos

---
- [Descrição](#descrição)
  - [Regras](#regras)
  - [Atenção](#atenção)
- [Instalação](#instalação)
  - [Clonar projeto](#clonar-projeto)
  - [Instalar dependências](#instalar-dependências)
  - [Iniciar servidor](#iniciar-servidor)
---

### Descrição

A API Agrupamento de Voos realiza uma consulta, em uma API externa, que retorna diversos voos e faz o agrupamento dos mesmos. É retornado um JSON com o resultado ordenado pelo menor preço do grupo.

#### Regras

O grupo pode ser formado por vários voos de ida e de volta respeitando as seguintes regras:

- O grupo deve ter pelo menos uma opção de ida e de volta.
- A tarifa de todos os voos do grupo deve ser a mesma.
- O valor total do grupo não pode sofrer alterações com as combinações dos valores dos voos de ida e de volta.

#### Atenção

Para fazer as combinações, a API leva em consideração apenas a tarifa e os valores dos voos. Não foi levado em consideração a origem e o destino de cada voo, assim como os horários de partida e chegada.

### Instalação

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
