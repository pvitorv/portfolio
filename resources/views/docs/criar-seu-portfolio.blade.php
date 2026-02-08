<x-layouts.app
    title="Como criar seu próprio portfólio"
    metaDescription="Guia passo a passo: clone o repositório do portfólio no GitHub, configure o banco de dados (MySQL, SQLite ou PostgreSQL) e publique seu site com Laravel."
    :canonicalUrl="route('docs.criar-seu-portfolio')"
>
    <div class="min-h-screen flex flex-col">
        <x-app-navbar brand="Portfólio" variant="glass">
            <x-slot:links>
                <a href="{{ url('/') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Início</a>
                <a href="{{ url('/#projetos') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Projetos</a>
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/5">Entrar</a>
            </x-slot:links>
            <x-app-theme-toggle />
        </x-app-navbar>

        <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12">
            <article class="prose prose-gray dark:prose-invert max-w-none">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Como criar seu próprio portfólio</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                    Este guia mostra como clonar o repositório <a href="https://github.com/pvitorv/portfolio" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline">github.com/pvitorv/portfolio</a>, configurar o ambiente e criar o banco de dados para rodar o projeto na sua máquina.
                </p>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">1. Requisitos</h2>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-400 space-y-1">
                        <li><strong>PHP 8.2+</strong></li>
                        <li><strong>Composer</strong></li>
                        <li><strong>Node.js e npm</strong> (para compilar CSS/JS com Vite; opcional no início)</li>
                        <li><strong>Git</strong></li>
                        <li><strong>Banco de dados:</strong> MySQL 8+, PostgreSQL ou SQLite</li>
                    </ul>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">2. Clonar o repositório</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Abra o terminal na pasta onde deseja criar o projeto e execute:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>git clone https://github.com/pvitorv/portfolio.git
cd portfolio</code></pre>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">3. Instalar dependências PHP</h2>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>composer install</code></pre>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">4. Configurar o ambiente</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Copie o arquivo de exemplo e gere a chave da aplicação:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto mb-4"><code>cp .env.example .env
php artisan key:generate</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Edite o arquivo <code class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-gray-700">.env</code> e ajuste pelo menos:</p>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-400 space-y-1 mb-4">
                        <li><code>APP_NAME</code> – nome do seu portfólio</li>
                        <li><code>APP_URL</code> – ex.: <code>http://localhost:8000</code></li>
                        <li>As variáveis de banco de dados (<code>DB_*</code>) conforme a seção abaixo.</li>
                    </ul>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">5. Criar o banco de dados</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Escolha uma das opções abaixo.</p>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-2">Opção A: MySQL / MariaDB</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">No MySQL (client ou phpMyAdmin), crie o banco e o usuário (se precisar):</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto mb-4"><code>CREATE DATABASE portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Opcional: criar usuário
-- CREATE USER 'portfolio'@'localhost' IDENTIFIED BY 'sua_senha';
-- GRANT ALL ON portfolio.* TO 'portfolio'@'localhost';
-- FLUSH PRIVILEGES;</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">No <code class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-gray-700">.env</code>:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=</code></pre>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-2">Opção B: SQLite</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">Crie o arquivo do banco e ajuste o <code class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-gray-700">.env</code>:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto mb-2"><code>touch database/database.sqlite</code></pre>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>DB_CONNECTION=sqlite
# DB_DATABASE=absolute/path/to/database/database.sqlite
# Ou deixe em branco: Laravel usa database/database.sqlite por padrão</code></pre>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-2">Opção C: PostgreSQL</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">No terminal (ou pgAdmin):</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto mb-2"><code>createdb portfolio</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">No <code class="px-1.5 py-0.5 rounded bg-gray-200 dark:bg-gray-700">.env</code>:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=portfolio
DB_USERNAME=postgres
DB_PASSWORD=</code></pre>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">6. Rodar as migrations</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Isso cria as tabelas (usuários, projetos, cache, filas, etc.):</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>php artisan migrate</code></pre>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">7. Criar seu usuário (admin)</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">O primeiro usuário do sistema é o “perfil” exibido na página inicial. Crie-o pelo Artisan Tinker:</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>php artisan tinker</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mb-2 mt-4">Dentro do Tinker, execute (troque nome, e-mail e senha pelos seus):</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>\App\Models\User::create([
    'name' => 'Seu Nome',
    'email' => 'seu@email.com',
    'password' => bcrypt('sua-senha-segura'),
]);</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">Digite <code>exit</code> para sair do Tinker.</p>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">8. Assets (opcional)</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Para compilar CSS e JavaScript com Vite (Tailwind e Alpine):</p>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>npm install
npm run build</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mt-3">Se não rodar <code>npm run build</code>, o projeto usa Tailwind e Alpine via CDN (fallback já configurado no layout).</p>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">9. Subir o servidor e acessar</h2>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>php artisan serve</code></pre>
                    <p class="text-gray-600 dark:text-gray-400 mt-4">Abra no navegador:</p>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-400 space-y-1">
                        <li><strong>Página inicial:</strong> <a href="http://localhost:8000" class="text-blue-600 dark:text-blue-400 hover:underline">http://localhost:8000</a></li>
                        <li><strong>Painel admin:</strong> <a href="http://localhost:8000/login" class="text-blue-600 dark:text-blue-400 hover:underline">http://localhost:8000/login</a> — use o e-mail e a senha que você criou no passo 7.</li>
                    </ul>
                    <p class="text-gray-600 dark:text-gray-400 mt-4">No admin você pode editar seu perfil (nome, foto, título, bio, redes sociais) e gerenciar os projetos exibidos na página inicial.</p>
                </section>

                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Resumo dos comandos</h2>
                    <pre class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>git clone https://github.com/pvitorv/portfolio.git
cd portfolio
composer install
cp .env.example .env
php artisan key:generate
# Criar o banco (MySQL/PostgreSQL/SQLite) e configurar DB_* no .env
php artisan migrate
php artisan tinker
# Dentro do tinker: \App\Models\User::create([...]);
npm install && npm run build   # opcional
php artisan serve</code></pre>
                </section>

                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Repositório: <a href="https://github.com/pvitorv/portfolio" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline">github.com/pvitorv/portfolio</a>
                </p>
            </article>
        </main>
    </div>
</x-layouts.app>
