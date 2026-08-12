# Histórico — Hub de links (Linktree pessoal)

**Período:** 12/08/2026  
**Branch base:** `020`  
**Produção:** https://www.portfolio.testes790.top  
**Repositório:** https://github.com/pvitorv/portfolio  

---

## O que foi construído

### Página pública `/links`
- Hub estilo Linktree para Instagram/WhatsApp (uso pessoal, não integrado na home do portfólio).
- Foto do perfil, nome, título e bio (vindos do **Admin → Perfil**).
- Cards **sem imagens de projetos** — só título + descrição curta.
- **Compartilhar** por link: Copiar, WhatsApp, Share API (Mais apps).
- Botão **Compartilhar página** no topo.
- Integração automática com:
  - **Links extras** (cadastro manual no admin)
  - **Projetos visíveis** (título, descrição, link + GitHub se existir)
  - **Perfil** (portfólio completo, LinkedIn, GitHub, e-mail, WhatsApp)

### Tracking de cliques
- Links extras: `/l/{id}` → incrementa contador + `bio_link_clicks`
- Projetos: `/l/p/{project}` e `/l/p/{project}/github`
- Perfil: `/l/s/{channel}` (`portfolio`, `linkedin`, `github`, `email`, `whatsapp`)

### Analytics no admin (`/admin/bio-links`)
- **Visitas à página** (`hub_page_views`)
- **Impressões por link** — quantas vezes cada link foi exibido (`hub_impressions`)
- **Cliques** por link (7 dias + total)
- **CTR** (cliques ÷ impressões)
- Tabela única com todos os links (extras + portfólio)
- Zerar estatísticas por item

---

## Commits principais (branch `020`)

| Commit | Descrição |
|--------|-----------|
| `9a70b9d` | Hub de links: CMS, models, migrations, rotas, views |
| `f662d8c` | Analytics, redesign `/links`, `LinkAnalyticsService` |
| `4669bfe` | Restaura foto do perfil na página de links |
| `714a118` | Admin resiliente se tabelas de analytics não existirem |
| `b67d707` | Fix TypeError `HubImpression::lastFor()` (Carbon vs string) |

---

## Migrations

```
2026_08_12_000001_create_bio_links_table
2026_08_12_000002_add_description_to_bio_links_and_hub_clicks
2026_08_12_000003_create_hub_analytics_tables
```

---

## SSH Hostoo

```bash
ssh -p 44991 q83af8ke@ssh.samtooweb.com
cd ~/portfolio
git pull origin 020
php artisan migrate --force
php artisan optimize:clear
php artisan route:clear
php artisan view:clear
```

**Importante:** comandos Laravel são sempre `php artisan ...` (nunca só `migrate` ou `optimize:clear`).

---

## URLs úteis

| URL | Função |
|-----|--------|
| `/links` | Página pública Linktree |
| `/admin/bio-links` | CMS + analytics |
| `/admin/profile` | Foto, nome, bio, redes |
| `/admin/projects` | Projetos que entram automaticamente no hub |

---

## Problemas resolvidos nesta sessão

1. **Deploy antes do push** — código local sem `git add/commit/push`; servidor recebia "Already up to date".
2. **`.env` local** — credenciais Hostoo (`portfolio790`) não funcionam no Laragon; migrate local falha até ajustar DB local.
3. **500 no admin** — tabelas `hub_impressions` / `hub_page_views` ausentes ou `HubImpression::lastFor()` retornando string em vez de Carbon.
4. **`cd ~/portfolio`** — caminho do servidor, não do PC Windows.

---

## Deploy futuro (fluxo padrão)

**No PC (após alterações):**
```bash
cd /c/laragon/www/NEWS-PROJECTS/portfolio
git add .
git commit -m "Descrição clara"
git push origin NNN
```

**No servidor:**
```bash
ssh -p 44991 q83af8ke@ssh.samtooweb.com
cd ~/portfolio
git pull origin NNN
php artisan migrate --force
php artisan optimize:clear
php artisan view:clear
```

O `.env`, banco MySQL e uploads em `storage/app/public/` permanecem só no servidor.
