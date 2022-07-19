<h1 align="center">
     <img alt="Boilerplate API Laravel" title="#Boilerplate" width="100" src="./public/laravel.png" /> 
</h1>
<h1 align="center">
Boilerplate API Laravel
</h1>

<h4 align="center"> 
	🚧 Criado Boilerplate API Laravel com qualquer front-end 🚀 em construção... 🚧
</h4>

<p align="center">
  <img alt="GitHub language count" src="https://img.shields.io/github/languages/count/vitoropereira/boilerplate_laravel_nextjs_api?color=%2304D361">
  <img alt="Repository size" src="https://img.shields.io/github/repo-size/vitoropereira/boilerplate_laravel_nextjs_api">
  <a href="https://github.com/vitoropereira/boilerplate_laravel_nextjs_api/commits/main">
    <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/vitoropereira/boilerplate_laravel_nextjs_api">
  </a>
   <a href="https://github.com/vitoropereira/boilerplate_laravel_nextjs_api/stargazers">
    <img alt="Stargazers" src="https://img.shields.io/github/stars/vitoropereira/boilerplate_laravel_nextjs_api?style=social">
  </a>
</p>


## 💻 Sobre o projeto

Eu Vitor Pereira, desenvolvi este boilerplate para _"startar"_ projetos com mais facilidades. A intenção é utilizar o backend com Laravel e frontend com NextJS. (Veja o repositório [Boilerplate-NextJS](https://github.com/vitoropereira/boilerplate_laravel_nextjs_app))

Esse projeto vem com as seguintes funcionalidades:
 * Criar conta no sistema;
 * Atualizar dados cadastrais;
 * Realizar login; e
 * Recupera senha;

Realizado deploy no Heroku em ambientes separados.
API em Laravel: 
[API-WEB](https://boilerplate-laravel-nextjs-api.herokuapp.com/)

## 🛠 Tecnologias

As seguintes ferramentas foram usadas na construção do projeto:

 [php](https://www.php.net/)<br>
 [guzzlehttp/guzzle](https://docs.guzzlephp.org/)<br>
 [laravel/framework](https://laravel.com/)<br>
 [laravel/sanctum](https://laravel.com/docs/9.x/sanctum)<br>

## 🚀 Como executar o projeto

### Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:
[Git](https://git-scm.com), [Node.js][nodejs]. 
Além disto é bom ter um editor para trabalhar com o código como [VSCode][vscode]

### 🧭 Rodando a aplicação

```bash
# Clone este repositório
$ git clone https://github.com/vitoropereira/boilerplate_laravel_nextjs_api.git

# Acesse a pasta do projeto no seu terminal/cmd
$ cd boilerplate_laravel_nextjs_api

# Instale as dependências
$ composer install

# Rodar as Migrations
$ php artisan migrate

# Execute a aplicação em modo de desenvolvimento
$ php artisan server

# A aplicação será aberta na porta:8000 - acesse http://localhost:8000
```

## 😯 Como contribuir para o projeto

1. Faça um **fork** do projeto.
2. Crie uma nova branch com as suas alterações: `git checkout -b my-feature`
3. Salve as alterações e crie uma mensagem de commit contando o que você fez: `git commit -m "feature: My new feature"`
4. Envie as suas alterações: `git push origin my-feature`
> Caso tenha alguma dúvida confira este [guia de como contribuir no GitHub](https://github.com/firstcontributions/first-contributions)


[typescript]: https://www.typescriptlang.org/
[classnames]: https://www.npmjs.com/package/classnames
[nextjs]: https://nextjs.org/
[reactjs]: https://reactjs.org
[reactZoomPanPinch]: https://www.npmjs.com/package/react-zoom-pan-pinch
[vscode]: https://code.visualstudio.com/
[nodejs]: https://nodejs.org/