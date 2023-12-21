# Fullstack Challenge - Onfly 20231205

## Descrição

Este projeto consiste em um CRUD de despesas simples, onde é possível o usuário criar suas conta e fazer a gerencia de suas despesas.

<div>
  <h3 align="left"><a href="https://www.loom.com/share/bd54f914db0540f68531eb79c8691b7a?sid=07591e8b-8cf1-4c12-8846-4693767e9f22"><i>Demonstração do Sistema</i></a></h3>
</div>

## Tecnologias

### FrontEnd

- JavaScript
- TypeScript
- Vue

### BackEnd

- PHP
- Laravel

### Banco

- MySQL (HEROKO)

## Instruções

### FrontEnd

- cd ./frontend
- npm install
- quasar dev

#### MAIL

Para realizar o envio de emails ao criar uma nova despesa, crie uma conta em mailtrap.io, e preencha as seguintes configurações no seu arquivo .env

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME={user}
MAIL_PASSWORD={pass}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS={email}
MAIL_FROM_NAME="${APP_NAME}"
```

### BackEnd

- cd ./backend
- composer install
- php artisan serve

## Banco

Para configurar o banco, crie uma nova aplicação no site da HEROKO e preencha os seguintes campos do arquivo .env

```
DB_CONNECTION=mysql
DB_HOST={host}.amazonaws.com
DB_PORT=3306
DB_DATABASE={datbase_name}
DB_USERNAME={User}
DB_PASSWORD=*****
```

## Testes

- cd ./backend
- php composer test

>  This is a challenge by [Coodesh](https://coodesh.com/)
