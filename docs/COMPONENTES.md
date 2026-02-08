# Documentação de componentes e recursos do portfolio

Use este arquivo para registrar cada novo componente ou recurso criado, com exemplos de uso. No final do projeto você pode publicar essa documentação num site externo.

---

## Mini CMS – Projetos

**O que é:** CRUD de projetos para exibir no portfolio. Cada projeto tem título, nome curto, descrição, URL externa e miniatura (screenshot da primeira página ou URL manual).

**Arquivos:**
- `app/Models/Project.php` – model
- `app/Http/Controllers/Admin/ProjectController.php` – controller
- `app/Services/ScreenshotService.php` – busca miniatura pela URL (API Microlink)
- `database/migrations/0001_01_01_000003_create_projects_table.php` – tabela `projects`
- `resources/views/admin/projects/` – index, create, edit, _form

**Rotas (prefixo `/admin`):**
- `GET /admin/projects` – listar projetos
- `GET /admin/projects/create` – formulário de criação
- `POST /admin/projects` – salvar novo projeto
- `GET /admin/projects/{id}/edit` – formulário de edição
- `PUT /admin/projects/{id}` – atualizar projeto
- `DELETE /admin/projects/{id}` – excluir projeto
- `POST /admin/projects/fetch-thumbnail` – (AJAX) buscar miniatura da URL do projeto

**Uso no front (welcome):** A rota `/` passa a variável `$projects` (projetos com `is_visible = true`, ordenados). A seção `#projetos` exibe cards com miniatura, descrição e botão "Ver projeto" (abre em nova aba).

**Campos do projeto (CRUD):**
- `title` – Título (obrigatório)
- `name` – Nome curto, opcional (se vazio, usa título)
- `description` – Descrição do projeto
- `url` – URL externa do projeto (obrigatório)
- `github_url` – Link do repositório no GitHub (opcional). Se preenchido, o card exibe um botão "GitHub" que abre em nova aba.
- Miniatura (uma das opções):
  1. **Print automático:** botão "Gerar print da página (screenshot)" – gera um screenshot da primeira página que a URL do projeto abre (API Microlink; pode falhar em alguns sites).
  2. **Enviar imagem:** upload de arquivo (JPG, PNG, GIF, WebP, até 2 MB). Arquivos vão para `storage/app/public/projects/` (é preciso rodar `php artisan storage:link` uma vez).
  3. **URL da imagem:** campo "Ou cole aqui a URL de uma imagem" para colar link de uma imagem.
- `order` – Ordem de exibição (número, menor primeiro)
- `is_visible` – Visível no portfolio (sim/não)

**Imagem no card:** A miniatura é exibida via `thumbnail_display_url` (accessor no model): uploads ficam em `storage/app/public/projects/` e são servidos em `/storage/projects/...`. Rode `php artisan storage:link` uma vez para criar o link simbólico `public/storage` → `storage/app/public`. Se a imagem falhar ao carregar, o card mostra um placeholder.

**Como usar:** Acesse `/admin/projects` para gerenciar. Para a miniatura: use o print automático; se não funcionar, envie uma imagem ou cole a URL de uma imagem.

---

## Card de projeto (seção Projetos na welcome)

**Onde:** `resources/views/welcome.blade.php`, seção `#projetos`.

**Comportamento:** Para cada projeto em `$projects`:
- Miniatura: imagem da `thumbnail_url` ou placeholder se vazio.
- Título: `display_name` (name ou title).
- Descrição: texto com `line-clamp-4`.
- Botão "Ver projeto": link para `$project->url` com `target="_blank"` e `rel="noopener noreferrer"`.

Não é um componente Blade reutilizável; está inline na welcome. Se quiser extrair para `<x-project-card :project="$project" />`, pode criar `resources/views/components/project-card.blade.php` e usar os mesmos blocos.

---

*Acrescente abaixo novos componentes e formas de uso.*
