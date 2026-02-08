<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .meta { color: #666; font-size: 14px; margin-bottom: 20px; }
        .message { background: #f5f5f5; padding: 16px; border-radius: 8px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <p><strong>{{ $senderName }}</strong> enviou uma mensagem pelo formulário de contato do portfolio.</p>
    <p class="meta">E-mail para resposta: {{ $senderEmail }}</p>
    <div class="message">{{ $messageBody }}</div>
</body>
</html>
