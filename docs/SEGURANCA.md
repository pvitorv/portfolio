# Verificação de segurança – portfolio.testes790.top

Resumo do que foi revisado e do que foi reforçado.

---

## O que já estava ok

| Item | Status |
|------|--------|
| **CSRF** | Formulários (contato, login, admin) usam `@csrf`. Layout envia token no meta. Laravel rejeita POST sem token. |
| **Autenticação** | Rotas `/admin/*` protegidas com `middleware('auth')`. Login usa `Auth::attempt()` e regenera sessão. Logout invalida sessão e token. |
| **Validação** | Contato: nome, e-mail, mensagem validados (tamanhos máximos). Login: e-mail e senha. Admin: validação em create/update de projetos e perfil. |
| **Mass assignment** | Models `User` e `Project` com `$fillable` definido; apenas campos permitidos são preenchidos em massa. |
| **XSS** | Blade usa `{{ }}` (escape automático) para exibir dados do perfil e projetos. Nenhum `{!! !!}` com entrada de usuário. |
| **SQL injection** | Uso de Eloquent e query builder com bindings; não há concatenação direta de input em SQL. |

---

## O que foi adicionado

- **Rate limit no login:** `throttle:5,1` → no máximo 5 tentativas de login por minuto por IP (reduz brute force).
- **Rate limit no contato:** `throttle:10,1` → no máximo 10 envios do formulário por minuto por IP (reduz spam).

Arquivo alterado: `routes/web.php`.

---

## Recomendações adicionais (opcional)

1. **Headers de segurança no servidor**  
   No Apache/Nginx ou no painel da Hostoo, você pode adicionar (se disponível):
   - `X-Frame-Options: SAMEORIGIN`
   - `X-Content-Type-Options: nosniff`
   - `Referrer-Policy: strict-origin-when-cross-origin`

2. **HTTPS**  
   Manter SSL ativo (já em uso em https://portfolio.testes790.top).

3. **Produção**  
   Manter `APP_DEBUG=false` e `APP_ENV=production` no `.env` do servidor.

4. **Senhas**  
   Usar senha forte no admin e no e-mail de serviço; trocar se houver suspeita de vazamento.

---

*Última verificação: fev/2026.*
