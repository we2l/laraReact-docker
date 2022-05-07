# LaraReact-docker

## 📜 Sobre:

Este é um ambiente Docker criado por mim para facilitar o desenvolvimento de aplicações em ambiente de desenvolvimento.

## 🕹 Tecnologias Utilizadas:

- Laravel 9
- React com Typescrit
- Mysql
- Nginx

## 📦 Como rodar o ambiente:

Primeiro passo é clonar o repositório.

```bash

  $ git clone https://github.com/we2l/laraReact-docker.git
  
  $ cd laraReact-docker

```

#### Após baixado o repositório, você precisa editar alguns arquivos: 

- Acesse o arquivo docker-compose.yml e edite os argumentos dos serviços docker, para que o container rode com seu usuário 

Rode os seguintes comandos na raiz do projeto: 

```bash
  $ cd api-laravel
  
  $ cp .env.example .env
```

- No arquivo .env criado, edite as variáveis de ambiente "DB_DATABASE", "DB_USERNAME" e "DB_PASSWORD" de acordo com suas necessidades, são essas variáveis que serão 
utilizadas no container do mysql.

----
Para rodar o ambiente, acesse a raiz do projeto e rode os seguintes comandos:

```bash
  $ docker-compose --env-file ./api-laravel/.env up -d
  
  $ docker-compose exec api bash
  
  $ composer install
  
  $ php artisan key:generate
```

#### ✔ Pronto, seu ambiente já está rodando e configurado. Para testar, acesse em seu navegador a url http://localhost:8080/ e http://localhost:3000/
