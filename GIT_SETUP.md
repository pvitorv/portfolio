# Conectar o projeto ao GitHub (pvitorv/portfolio)

Execute os comandos abaixo **na pasta do projeto** (`c:\laragon\www\NEWS-PROJECTS\portfolio`), no **PowerShell** ou **CMD** (evite Git Bash se der erro).

---

## Se o repositório no GitHub está **vazio** (sem README, sem arquivos)

```bash
git init
git add .
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/pvitorv/portfolio.git
git push -u origin main
```

---

## Se você já criou o repositório **com README** no GitHub

O remoto já tem um commit. Use um destes fluxos:

### Opção A – Manter o README do GitHub e juntar com seu projeto

```bash
git init
git add .
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/pvitorv/portfolio.git
git pull origin main --allow-unrelated-histories
# Resolva conflitos no README se aparecerem; depois:
git add .
git commit -m "Merge with remote"
git push -u origin main
```

### Opção B – Substituir o conteúdo do GitHub pelo do projeto (seu README do Laravel fica)

```bash
git init
git add .
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/pvitorv/portfolio.git
git push -u origin main --force
```

**Atenção:** `--force` sobrescreve o que está no GitHub. Use só se tiver certeza de que não precisa do que já está lá.

---

## Se o projeto **já tem** `git init` e você só quer apontar para o GitHub

```bash
git remote add origin https://github.com/pvitorv/portfolio.git
git branch -M main
git add .
git commit -m "first commit"
git push -u origin main
```

Se o remoto já existir e estiver errado:

```bash
git remote remove origin
git remote add origin https://github.com/pvitorv/portfolio.git
git push -u origin main
```

---

O `.gitignore` do Laravel já evita enviar `.env`, `vendor`, `node_modules`, etc. Só o código do projeto vai para o GitHub.
