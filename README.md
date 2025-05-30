## Sistema de Processamento de Pagamentos

Este projeto é um sistema de processamento de pagamentos integrado ao ambiente de homologação do Asaas, oferecendo opções de pagamento por boleto, cartão e Pix. O projeto foi desenvolvido com PHP no framework Laravel 11 e inclui autenticação com Laravel Sanctum, processamento de pagamentos e cadastro de clientes no Asaas utilizando processamento assíncrono com filas. Também possui um endpoint para receber requisições do webhook do Asaas.\
O ambiente de desenvolvimento está configurado via Laravel Sail, e os testes automatizados são realizados com Pest PHP, com cobertura principalmente sobre os recursos de criação e autenticação do usuário e os recursos de pagamento.\
O frontend utiliza componentes Vue.js juntamente com Blade.

### Funcionalidades

- Autenticação com Laravel Sanctum
- Processamento de pagamentos via Boleto, Cartão e Pix utilizando filas para processamento assíncrono
- Cadastro de clientes no Asaas utilizando filas para processamento assíncrono
- Endpoint para receber requisições do webhook do Asaas
- Utilização do ngrok para disponibilizar o ambiente online para o webhook
- Frontend com Vue.js e Blade
- Ambiente de desenvolvimento configurado com Laravel Sail
- Testes automatizados com Pest PHP

### Requisitos

- PHP >= 8.2
- Composer
- Docker
- Docker Compose
- Node.js
- NPM ou Yarn
- Ngrok (para receber requisições de webhook)

### Configuração do Ambiente de Desenvolvimento

#### Passo 1: Clonar o Repositório
```sh
git clone git@github.com:bholiveiradev/integracao-asaas.git
cd integracao-asaas
```
#### Passo 2: Configurar o Laravel Sail

1. Copie o arquivo `.env.example` para `.env`:
```sh
cp .env.example .env
```

2. Atualize as configurações no arquivo `.env` conforme necessário, incluindo as credenciais do Asaas e as configurações do banco de dados e de fila.
```sh
APP_FAKER_LOCALE=pt_BR

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=integracao-asaas
DB_USERNAME=sail
DB_PASSWORD=password

SESSION_DRIVER=redis

QUEUE_CONNECTION=redis

CACHE_STORE=redis

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

ASAAS_API_URL="https://sandbox.asaas.com/api/v3"
ASAAS_API_KEY="\$aact_YTU5YTE0..."
ASAAS_WEBHOOK_SIGNATURE=sua_assinatura_asaas_webhook
```

3. Instale as dependências do composer:
```sh
composer install
```

4. Inicie os containers com o Laravel Sail:
```sh
./vendor/bin/sail up -d
```

5. Execute as migrations:
```sh
./vendor/bin/sail artisan migrate
```
> Opcionalmente, as migrations podem ser executadas com a opção `--seed`

6. Gere a chave da aplicação:
```sh
./vendor/bin/sail artisan key:generate
```

#### Passo 3: Configurar o ngrok

1. Faça o download e instale o ngrok seguindo as instruções da [documentação oficial](https://ngrok.com/docs/getting-started/).

2. Inicie o ngrok para expor sua aplicação local:
```sh
ngrok http <porta-da-aplicação>
```
**Nota:** A porta padrão para o Laravel Sail é 80.

3. Atualize a URL de webhook no Asaas com o URL fornecido pelo ngrok, conforme guia de integração: [Sobre os Webhooks](https://docs.asaas.com/docs/sobre-os-webhooks).

4. Execute a fila em um terminal:
```sh
./vendor/bin/sail artisan queue:work
```

#### Passo 4: Configurar o Frontend

1. Instale as dependências do npm:
```sh
npm install
```

2. Execute o servidor de desenvolvimento do Vue.js:
```sh
npm run dev
```

#### Passo 5: Executar Testes Unitários

Execute os testes automatizados com Pest PHP:
```sh
./vendor/bin/sail pest
```
> **Nota:** Se preferir, execute os testes automaizados com o coverage em html:
```sh
./vendor/bin/sail pest --coverage-html coverage/
```
Dessa forma, o pest criará uma pasta no root do projeto, basta exectar o `index.html` dentro da pasta `coverage` (se tiver o plugin live server no VS Code, basta clicar com o botão direito e "Open with Live Server").

### Importe a Collection do Insomnia
- Abra o Insomnia.
- Vá até Create > Import > Select `+ File`.
- Arraste ou abra o arquivo `insomnia-collection.json` que está no root do projeto clique em Scan.

### Endpoints Disponíveis

#### Autenticação
- POST /api/login - Endpoint para login de usuários
- POST /api/register - Endpoint para registro de novos usuários
- POST /api/me - Endpoint para retornar os dados do usuário autenticado
- POST /api/logout - Endpoint para logout do usuário

#### Processamento de Pagamentos
- POST /api/payments - Endpoint para criação de pagamentos (boleto, cartão e Pix)
- GET /api/payments - Endpoint para listar os pagamentos do usuário autenticado
- GET /api/payments/{payment} - Endpoint para retornar um pagamento por ID

#### Webhook
- POST /api/payments/asaas/webhook - Endpoint para receber notificações de webhook do Asaas

### Documentação
- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Asaas API - Guia de Integração](https://docs.asaas.com/docs/)
- [Ngrok Documentation](https://ngrok.com/docs/getting-started/)
