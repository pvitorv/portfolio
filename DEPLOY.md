# Deploy na Hostoo (portfolio.testes790.top)

Domínio: **https://www.portfolio.testes790.top** (ou sem `www`, conforme o painel)

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
- **APP_URL** – use a URL exata do site (ex.: `https://www.portfolio.testes790.top` se o domínio abre com `www`)
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

### 1.3 Imagens (foto do perfil e miniaturas dos projetos)

Os uploads ficam em `~/portfolio/storage/app/public/` (pastas `profile/` e `projects/`). O site acessa por `/storage/...`.

**Na Hostoo**, se o document root for `public_html` e o Laravel estiver em `~/portfolio`:

```bash
cd ~/public_html
ln -sf ../portfolio/storage/app/public storage
ls -la storage
ls -la ../portfolio/storage/app/public/projects
```

Confirme que os arquivos `.jpg`/`.png` existem em `projects/` e `profile/`. Eles **não vão pelo Git**; se sumirem após atualização do servidor, reenvie pelo admin ou copie do seu PC.

Se `php artisan storage:link` falhar (pasta `public` inexistente no projeto), use o `ln -s` acima em `public_html`.

---

## 2. Comandos após subir o código

```bash
cd ~/portfolio
composer install --no-dev --optimize-autoloader
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

- Document root: pasta **`public`** do Laravel **ou** `public_html` com symlink `storage` apontando para `portfolio/storage/app/public`.
- PHP 8.2+ (ou a versão que a Hostoo oferecer e que o Laravel suportar).

---

## 4. Imagens não aparecem (troubleshooting)

1. **Symlink:** em `public_html`, existe `storage` → `../portfolio/storage/app/public`?
2. **Arquivos:** `ls ~/portfolio/storage/app/public/projects` lista as miniaturas?
3. **Teste no navegador:** abra `https://www.portfolio.testes790.top/storage/projects/NOME_DO_ARQUIVO.jpg` (nome gravado no banco).
4. **Reupload:** Admin → Projetos → editar → enviar miniatura de novo.
5. Após mudar `.env`, rode `php artisan config:cache`.

---

## 5. Rodar de novo em local (Laragon)

Para desenvolver na máquina:

1. Copie o `.env` de produção (ou use um `.env.local`) e ajuste:
2. Descomente as linhas marcadas como **Local** no `.env.example` (APP_ENV, APP_DEBUG, APP_URL, DB_*, MAIL_*).
3. Comente as linhas de **Produção** correspondentes, se estiver usando o mesmo arquivo.
4. Rode `php artisan storage:link` na raiz do projeto (Laragon usa `public/storage`).

Ou mantenha dois arquivos: `.env` (produção) e `.env.local` (local) e use o que for necessário em cada ambiente.
