# Sincronizar Hostoo ↔ GitHub via SSH

Objetivo: trazer o código **mais completo da Hostoo** para o GitHub/local, e depois evoluir sempre **GitHub → local → Hostoo**.

Domínio: **https://www.portfolio.testes790.top**  
Estrutura na Hostoo (padrão):

- Laravel: `~/portfolio`
- Document root: `~/public_html` (symlink `public_html/storage` → `../portfolio/storage/app/public`)

---

## Visão geral (2 fases)

| Fase | Direção | O que faz |
|------|---------|-----------|
| **1** | Hostoo → PC → GitHub | Igualar repositório ao que está no ar |
| **2** | GitHub → Hostoo | `git pull` no servidor após cada melhoria |

O `.env`, uploads em `storage/app/public/` e o banco **não vão pelo Git** — ficam só no servidor (e cópia local do `.env` para dev).

---

## Pré-requisitos SSH

No **Windows**, use PowerShell, CMD ou Git Bash com:

```bash
ssh usuario@seu-servidor.hostoo.com.br -p PORTA
```

(substitua usuário, host e porta conforme o painel Hostoo)

Teste:

```bash
ssh usuario@HOST -p PORTA "ls -la ~/portfolio"
```

---

## Fase 1 — Trazer Hostoo para o GitHub (primeira vez)

### Passo 1: Baixar o projeto da Hostoo para o PC

Na pasta **pai** do projeto local (ex.: `NEWS-PROJECTS`), crie backup e baixe:

```bash
# Backup do local atual (opcional)
mv portfolio portfolio-backup-local

# Baixar da Hostoo (ajuste user, host, porta)
rsync -avz -e "ssh -p PORTA" \
  usuario@HOST:~/portfolio/ \
  ./portfolio/ \
  --exclude vendor \
  --exclude node_modules \
  --exclude .env \
  --exclude storage/logs \
  --exclude storage/framework/cache \
  --exclude storage/framework/sessions \
  --exclude storage/framework/views \
  --exclude bootstrap/cache/*.php \
  --exclude public/hot
```

Se `rsync` não existir no Windows, use **SCP** ou **WinSCP/FileZilla (SFTP)** com os mesmos excludes, ou no **servidor**:

```bash
cd ~
tar czf portfolio-codigo.tar.gz portfolio \
  --exclude=portfolio/vendor \
  --exclude=portfolio/node_modules \
  --exclude=portfolio/.env \
  --exclude=portfolio/storage/logs \
  --exclude=portfolio/storage/framework \
  --exclude=portfolio/bootstrap/cache \
  --exclude=portfolio/public/hot
```

Depois baixe `portfolio-codigo.tar.gz` e extraia no PC.

### Passo 2: Entrar no repo local e conferir

```bash
cd portfolio
git status
git remote -v
```

Se veio da Hostoo **sem** pasta `.git`, inicialize e conecte ao GitHub:

```bash
git init
git remote add origin https://github.com/pvitorv/portfolio.git
git fetch origin
git checkout -b sync-hostoo
```

Se já existir `.git` local (seu Laragon), **substituir arquivos** pelos da Hostoo (menos `.env`, `vendor`, `node_modules`) e depois:

```bash
git status
git diff
```

### Passo 3: Commit e push para o GitHub

```bash
git add .
git commit -m "Sincroniza código com produção Hostoo"
git push -u origin sync-hostoo
```

Revise no GitHub. Se estiver ok, merge na branch principal:

```bash
git checkout main
git merge sync-hostoo
git push origin main
```

(ou use PR no GitHub se preferir)

### Passo 4: Não commitar por engano

Nunca subir ao Git:

- `.env` (senhas, SMTP, DB)
- `vendor/`, `node_modules/`
- `storage/app/public/profile/*`, `storage/app/public/projects/*` (uploads)
- `bootstrap/cache/*.php` gerados no servidor

O `.gitignore` do projeto já cobre a maior parte.

---

## Fase 2 — Hostoo puxa do GitHub (rotina)

### Uma vez: clonar ou apontar Git na Hostoo

**Se `~/portfolio` ainda não tem Git:**

```bash
ssh usuario@HOST -p PORTA

cd ~
mv portfolio portfolio-old-backup
git clone https://github.com/pvitorv/portfolio.git portfolio
cp portfolio-old-backup/.env portfolio/.env
# Recriar symlink storage em public_html se necessário
cd ~/public_html
ln -sf ../portfolio/storage/app/public storage
```

**Se já tem Git no servidor:**

```bash
cd ~/portfolio
git remote -v
git remote set-url origin https://github.com/pvitorv/portfolio.git
git fetch origin
git checkout main
git pull origin main
```

Use **token do GitHub** ou **SSH key** no servidor se o clone/pull pedir senha.

### Deploy após cada `git pull`

```bash
cd ~/portfolio
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci && npm run build
```

Se não rodar `npm` no servidor, faça `npm run build` no PC e suba só `public/build/` (ou inclua build no CI depois).

### Script rápido no servidor (opcional)

Crie `~/portfolio/deploy.sh`:

```bash
#!/bin/bash
set -e
cd ~/portfolio
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Deploy OK"
```

```bash
chmod +x ~/portfolio/deploy.sh
./deploy.sh
```

---

## Fluxo do dia a dia (depois de sincronizado)

1. **PC (Laragon):** editar código, testar local  
2. **PC:** `git add`, `commit`, `push` → GitHub  
3. **Hostoo SSH:** `cd ~/portfolio && git pull && ./deploy.sh`  
4. Testar **https://www.portfolio.testes790.top**

---

## Banco de dados e arquivos

| Item | Git? | Onde fica |
|------|------|-----------|
| Código PHP/Blade/JS | Sim | GitHub |
| `.env` | Não | Só Hostoo + cópia local `.env` |
| Miniaturas / foto perfil | Não | `storage/app/public/` na Hostoo |
| Banco MySQL | Não | Hostoo (backup pelo painel) |

Se o admin na Hostoo tiver dados que o local não tem, **não precisa** exportar DB para o Git — só mantenha o MySQL de produção na Hostoo.

---

## Troubleshooting SSH

| Problema | Solução |
|----------|---------|
| Timeout na porta 22 | Use a **porta SSH** que a Hostoo informa no painel |
| `git pull` pede senha | Configure [Personal Access Token](https://github.com/settings/tokens) ou SSH key no servidor |
| Site quebra após pull | `php artisan config:clear && php artisan cache:clear` e confira `.env` |
| Imagens sumiram | Symlink `public_html/storage`; arquivos em `storage/app/public/` |
| Permissão negada | `chmod -R ug+rwx storage bootstrap/cache` |

---

## Resumo

**Sim, é possível.** Ordem correta:

1. SSH: baixar/copiar código da Hostoo → PC  
2. PC: commit → GitHub (estado = produção)  
3. SSH na Hostoo: `git remote` + `git pull` daqui pra frente  
4. Melhorias: PC → push → SSH pull + deploy  

Assim Hostoo e GitHub ficam alinhados; o que está “mais completo” na Hostoo vira a base do repositório.
