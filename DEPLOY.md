# Deploy na Hostoo (portfolio.testes790.top)

Domínio: **https://portfolio.testes790.top**

E-mail do site: **paulovitor@portfolio.testes790.top** (SMTP para envio do formulário de contato)

---

## 1. Configuração no servidor

### 1.1 Copiar ambiente

No servidor, na pasta do projeto:

```bash
cp .env.example .env
```

Edite o `.env` e confira:

- **APP_KEY** – gere se ainda não tiver: `php artisan key:generate`
- **APP_URL** – deve ser `https://portfolio.testes790.top`
- **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD** – dados do MySQL que a Hostoo forneceu
- **MAIL_PASSWORD** – senha do e-mail `paulovitor@portfolio.testes790.top`

### 1.2 E-mail (SMTP)

No `.env` de produção já vêm preenchidos:

- `MAIL_MAILER=smtp`
- `MAIL_HOST=mail.portfolio.testes790.top`
- `MAIL_PORT=465`
- `MAIL_USERNAME=paulovitor@portfolio.testes790.top`
- `MAIL_ENCRYPTION=ssl`
- `MAIL_FROM_ADDRESS="paulovitor@portfolio.testes790.top"`

Basta definir **MAIL_PASSWORD** com a senha desse e-mail.

---

## 2. Comandos após subir o código

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Se usar build de front (Vite):

```bash
npm ci
npm run build
```

---

## 3. Documento raiz e PHP

- Document root do domínio deve apontar para a pasta **`public`** do projeto.
- PHP 8.2+ (ou a versão que a Hostoo oferecer e que o Laravel suportar).

---

## 4. Rodar de novo em local (Laragon)

Para desenvolver na máquina:

1. Copie o `.env` de produção (ou use um `.env.local`) e ajuste:
2. Descomente as linhas marcadas como **Local** no `.env.example` (APP_ENV, APP_DEBUG, APP_URL, DB_*, MAIL_*).
3. Comente as linhas de **Produção** correspondentes, se estiver usando o mesmo arquivo.

Ou mantenha dois arquivos: `.env` (produção) e `.env.local` (local) e use o que for necessário em cada ambiente.
