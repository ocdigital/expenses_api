<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

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
    git clone https://github.com/ocdigital/expenses_api.git
    ```

2. **Acesse o Diretório do Projeto:**
    ```bash
    cd expenses_api
    ```

3. **Execute o Ambiente em Modo de Segundo Plano:**
    ```bash
    docker-compose up -d
    ```

4. **Para executar os Testes:**
    ```bash
    docker exec -it expenses_api-app-1 php artisan test
    ```

4. **Usuário Admin**    
    Será criado um usuario admin para fazer o primeiro login para gerar o token da api

    admin@teste.com

    password123
  


## Documentação da API

Explore a documentação da API em http://localhost:8000/api/documentation.

Caso queria utilizar Postman, há um arquivo de configuração na raiz do projeto: expenses_api.postman_collection.json

## Algumas informações

Aplicação: http://localhost:8000.

Horizon: http://localhost:8000/horizon.

MailCatcher: http://localhost:1080.

O backend está utilizando token para atenticação, então é necessário fazer login na api
para gerar o token.

Passos para gerar a despesa:
-Faça o login com admin e adicione o token a ferramenta (postman ou no Swagger).

-Crie um usuário.

-Crie um novo cartão atribuindo o id do usuário. 

-Crie uma nova despesa utilizando o numero do cartão.

-Pode visualizar os emails no MailCatcher e os logs no Horizon 😀
