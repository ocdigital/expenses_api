# Desafio Back-end - Expenses API

Este projeto é uma API REST desenvolvida para o desafio de desenvolvedor back-end.


Nossa API vai ter endpoints que possibilitam

* Crie um sistema que simula de forma básica o cadastro de despesas de um usuário a partir de um cartão.
* Para esse sistema é desejado que tenha dois tipos de usuários, um que seja do tipo administrador e outro que seja do tipo comum;
* Cada usuário pode ter quantos cartões quiser, e cada cartão pode ter um número ilimitado de despesas;
* O usuário administrador pode ter acesso a visualização de todos os cartões e todos os usuários e todas as suas despesas;
* O usuário comum pode apenas acessar seu cartão e suas despesas;
* Ao receber um evento de cadastro de despesa deve ser disparado um email para todos os administradores e também para o usuário do cartão no momento que a despesa foi gerada;
* Para uma despesa ser criada , é necessário que exista saldo no cartão do usuário, e este saldo precisa ser atualizado toda vez que for criada uma despesa.
* Não permitir usuários duplicados.
* Cartão deve possuir no mínimo os atributos de número do cartão, saldo.
* Uma transação de geração de despesa só pode acontecer quando houver saldo disponível
* Saldos de cartão não podem ser negativos
* Despesas não podem ser negativas.
* A operação de geração de despesa deve ser uma transação (em caso de inconsistência deve ser revertida) e o dinheiro deve voltar para o saldo do cartão do usuário que envia.
* Construir apenas a API com todas as rotas necessárias
* Criar
* Listar
* Deletar
* Atualizar


## Instalação

1. **Clone o Repositório:**
    ```bash
    https://github.com/ocdigital/expenses_api.git
    ```

2. **Acesse o Diretório do Projeto:**
    ```bash
    cd expenses_api
    ```

5. **Execute o Ambiente em Modo de Segundo Plano:**
    ```bash
    docker-compose up -d
    ```

7. **Entre no Container Backend para Executar as Migrações:**
    *(Encontre o nome do container, pode ser expenses_api-app)*
    ```bash
    docker ps
    docker exec -it nome-do-container bash
    ```

8. **Execute as Migrações do Banco de Dados:**
    ```bash
    php artisan migrate
    ```

9. **Vamos popular a base com um usuário Administrador:**
    ```bash
    php artisan db:seed
    ```

10. **Para executar os Testes:**
    ```bash
    php artisan test
    ```

## Documentação da API

Explore a documentação da API em http://localhost:8000/api/documentation.

## Algumas informações

A apliação Backend irá inicianizar em http://localhost:8000/.

Foi instalado o Horizon para visualizar os Jobs http://localhost:8000/horizon.

Existe um serviço de email fake MailCatcher no endereço http://localhost:1080/, os emails serão enviados para lá.

O backend está utilizando token para atenticação, então é necessário fazer login na api
para gerar o token.
