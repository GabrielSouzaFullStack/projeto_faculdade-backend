<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quero Adotar - ONG Amigos de Rua</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 0;
    }

    .form-container {
      max-width: 700px;
      margin: 40px auto;
      background: #fdf3de;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 18px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    h2 {
      margin-top: 25px;
      font-size: 20px;
    }

    h3 {
      margin-top: 15px;
      font-size: 16px;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-top: 8px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
    }

    .group {
      margin-bottom: 20px;
    }

    .options {
      margin-top: 10px;
    }

    .options label {
      margin-left: 6px;
      margin-right: 20px;
    }

    input[type="submit"],
    button[type="submit"] {
      width: 100%;
      background: #4caf50;
      border: none;
      padding: 14px;
      font-size: 16px;
      color: #fff;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 20px;
    }

    input[type="submit"]:hover,
    button[type="submit"]:hover {
      background: #45a049;
    }

    .back-button {
      display: inline-block;
      margin-bottom: 20px;
      color: #4caf50;
      text-decoration: none;
      font-weight: bold;
    }

    .back-button:hover {
      text-decoration: underline;
    }

    /* Mensagens de feedback */
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      display: none;
    }

    .alert.show {
      display: block;
    }

    .alert-success {
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
    }

    .alert-error {
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
      color: #721c24;
    }

    .loading {
      display: none;
      text-align: center;
      padding: 20px;
    }

    .loading.show {
      display: block;
    }
  </style>
</head>

<body>
  <div class="form-container">
    <a href="../index.php" class="back-button">← Voltar para o site</a>

    <div id="alertMessage" class="alert"></div>
    <div id="loading" class="loading">
      <p>Enviando formulário... Por favor aguarde.</p>
    </div>

    <form action="../includes/processar-adocao.php" method="post" id="formularioAdocao">
      <h1>Quero adotar!</h1>

      <div class="group">
        <h2>Nome completo</h2>
        <input
          type="text"
          name="nome"
          placeholder="Digite o seu nome completo"
          required />
      </div>

      <div class="group">
        <h2>Telefone</h2>
        <input
          type="text"
          name="telefone"
          data-mask="telefone"
          placeholder="(11) 98765-4321"
          maxlength="15"
          required />
      </div>

      <div class="group">
        <h2>CPF</h2>
        <input
          type="text"
          name="cpf"
          data-mask="cpf"
          placeholder="123.456.789-00"
          maxlength="14"
          required />
      </div>

      <div class="group">
        <h2>Endereço</h2>
        <input
          type="text"
          name="endereco"
          placeholder="Digite o seu endereço completo"
          required />
      </div>

      <div class="group">
        <h2>Composição familiar</h2>

        <h3>Há crianças?</h3>
        <div class="options">
          <input
            id="ha_criancas_sim"
            type="radio"
            name="ha_criancas"
            value="Sim" />
          <label for="ha_criancas_sim">Sim</label>
          <input
            id="ha_criancas_nao"
            type="radio"
            name="ha_criancas"
            value="Nao" />
          <label for="ha_criancas_nao">Não</label>
        </div>

        <h3>Tem outros animais?</h3>
        <div class="options">
          <input
            id="tem_outros_animais_sim"
            type="radio"
            name="tem_outros_animais"
            value="Sim" />
          <label for="tem_outros_animais_sim">Sim</label>
          <input
            id="tem_outros_animais_nao"
            type="radio"
            name="tem_outros_animais"
            value="Nao" />
          <label for="tem_outros_animais_nao">Não</label>
        </div>

        <input
          type="text"
          name="detalhes_animais"
          placeholder="Se sim, quantos animais?" />
        <input
          type="text"
          name="cachorro"
          placeholder="Quantidade de cachorros" />
        <input
          type="text"
          name="cachorro_castrado"
          placeholder="Cachorros castrados?" />
        <input type="text" name="gato" placeholder="Quantidade de gatos" />
        <input
          type="text"
          name="gato_castrado"
          placeholder="Gatos castrados?" />
      </div>

      <div class="group">
        <h2>FIV/FELV</h2>
        <div class="options">
          <input
            id="fiv_felv_positivo"
            type="radio"
            name="fiv_felv"
            value="Positivo" />
          <label for="fiv_felv_positivo">Positivo</label>
          <input
            id="fiv_felv_negativo"
            type="radio"
            name="fiv_felv"
            value="Negativo" />
          <label for="fiv_felv_negativo">Negativo</label>
          <input
            id="fiv_felv_nunca"
            type="radio"
            name="fiv_felv"
            value="Nunca testado" />
          <label for="fiv_felv_nunca">Nunca testado</label>
        </div>
      </div>

      <div class="group">
        <h2>Local do animal que será adotado</h2>
        <div class="options">
          <input id="local_patio" type="radio" name="local" value="Patio" />
          <label for="local_patio">Pátio</label>
          <input id="local_casa" type="radio" name="local" value="Casa" />
          <label for="local_casa">Casa</label>
        </div>
      </div>

      <div class="group">
        <h2>Interesse de adoção</h2>
        <div class="options">
          <input
            id="tipo_animal_cachorro"
            type="checkbox"
            name="tipo_animal"
            value="Cachorro" />
          <label for="tipo_animal_cachorro">Cachorro</label>
          <input
            id="tipo_animal_gato"
            type="checkbox"
            name="tipo_animal"
            value="Gato" />
          <label for="tipo_animal_gato">Gato</label>
        </div>
      </div>

      <div class="group">
        <h2>Preferência de porte</h2>
        <div class="options">
          <input
            id="porte_animal_p"
            type="checkbox"
            name="porte_animal"
            value="P" />
          <label for="porte_animal_p">P</label>
          <input
            id="porte_animal_m"
            type="checkbox"
            name="porte_animal"
            value="M" />
          <label for="porte_animal_m">M</label>
          <input
            id="porte_animal_g"
            type="checkbox"
            name="porte_animal"
            value="G" />
          <label for="porte_animal_g">G</label>
        </div>
      </div>

      <div class="group">
        <h2>Preferência por idade</h2>
        <div class="options">
          <input
            id="idade_filhote"
            type="checkbox"
            name="idade"
            value="Filhote" />
          <label for="idade_filhote">Filhote</label>
          <input
            id="idade_adulto"
            type="checkbox"
            name="idade"
            value="Adulto" />
          <label for="idade_adulto">Adulto</label>
        </div>
      </div>

      <div class="group">
        <h2>Mais informações</h2>
        <textarea
          name="mais_informacoes"
          rows="6"
          placeholder="Escreva aqui mais informações que achar relevante"></textarea>
      </div>

      <input type="submit" value="Enviar" />
    </form>
  </div>

  <script src="../public/js/formulario-handler.js"></script>
  <script>
    // Inicializa o formulário de adoção
    inicializarFormularioAjax('formularioAdocao', '../includes/processar-adocao.php');
  </script>

  <!-- Script de máscaras para telefone e CPF -->
  <script src="../public/js/mascaras.js"></script>
</body>

</html>