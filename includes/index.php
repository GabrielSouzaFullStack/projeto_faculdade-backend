<?php
// Proteção contra acesso direto
// Este arquivo não deve ser acessado diretamente via navegador
http_response_code(403);
die('Acesso negado');
