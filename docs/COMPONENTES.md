# Documentação de componentes e recursos do portfolio

Use este arquivo para registrar cada novo componente ou recurso criado, com exemplos de uso. No final do projeto você pode publicar essa documentação num site externo.

---

## Autenticação e acesso ao admin

**Acesso:** Apenas por **/login**. Não há view pública de admin; o usuário faz login e passa a acessar todo o conteúdo em **/admin**.

**Rotas:**
- `GET /login` – formulário de login
- `POST /login` – autenticar (redireciona para `/admin/projects`)
- `POST /logout` – sair (form no admin)

**Proteção:** Todas as rotas em `/admin/*` usam o middleware `auth`. Se não estiver logado, o Laravel redireciona para `/login`.

**Criar o primeiro usuário:** Não há tela de registro (projeto de um único usuário). Crie o usuário no banco ou via tinker, por exemplo:
```bash
php artisan tinker
User::create(['name' => 'Seu Nome', 'email' => 'seu@email.com', 'password' => bcrypt('sua-senha')]);
```

---

## Perfil do usuário (currículo + foto)

**O que é:** Configurações de perfil do único usuário: nome, e-mail, foto, título profissional, texto sobre/currículo, telefone, localização, LinkedIn, GitHub e alteração de senha.

**Onde:** Admin → **Perfil** (`/admin/profile`). Tudo em admin, sem view pública de edição.

**Arquivos:**
- `app/Http/Controllers/Admin/ProfileController.php` – edit, update
- `resources/views/admin/profile/edit.blade.php` – formulário
- Migration `0001_01_01_000005_add_profile_fields_to_users_table.php` – campos em `users`: photo, title, bio, phone, location, linkedin_url, github_url

**Uso na página inicial:** A rota `/` usa `User::first()` como `$profile`. Na welcome são exibidos: nome, foto (ou iniciais), título, bio (seção "Sobre mim"), e no rodapé os links LinkedIn e GitHub do perfil. A estatística "Projetos" usa a quantidade de projetos.

**Foto:** Upload em `storage/app/public/profile/`. É necessário `php artisan storage:link`.

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

---

## Contato (rodapé)

**Onde:** Na própria página inicial, seção **Contato** com `id="contato"` (link do menu leva até ela). Fica no rodapé, logo acima da linha de copyright.

**Estrutura:**
- **Título** "Contato"
- **Coluna esquerda:** texto de chamada, e-mail (mailto), telefone (tel:), localização e links LinkedIn/GitHub (dados do perfil do usuário)
- **Coluna direita:** formulário com nome, e-mail e mensagem; envio via **POST /contact**

**Fluxo:** O visitante preenche o formulário e envia; o Laravel valida e envia um e-mail para o e-mail do perfil (primeiro usuário) com o conteúdo da mensagem. O remetente do e-mail recebe e pode responder diretamente (reply-to é o e-mail de quem enviou).

**Arquivos:** `app/Http/Controllers/ContactController.php`, `app/Mail/ContactMessageMail.php`, `resources/views/emails/contact-message.blade.php`. Rota: `POST /contact` → `contact.store`.

**E-mail:** Configure `MAIL_*` no `.env` para envio real (SMTP, etc.). Com `MAIL_MAILER=log`, as mensagens são gravadas em `storage/logs/laravel.log` (útil para testar).

---

*Acrescente abaixo novos componentes e formas de uso.*
