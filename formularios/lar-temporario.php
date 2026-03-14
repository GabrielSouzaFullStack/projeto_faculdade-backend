<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lar Temporário - ONG Amigos de Rua</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 90%;
      max-width: 800px;
      margin: 30px auto;
      background: #fdf3de;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .grupo {
      margin-bottom: 25px;
    }

    .grupo h2,
    .grupo h3 {
      margin-bottom: 10px;
      font-size: 18px;
    }

    input[type="text"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 5px;
      font-size: 15px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
    }

    .opcoes {
      margin-top: 10px;
    }

    .opcoes label {
      margin-left: 6px;
      margin-right: 15px;
    }

    .spacing-top {
      margin-top: 10px;
    }

    input[type="submit"],
    button[type="submit"] {
      width: 100%;
      padding: 14px;
      background: #4caf50;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }

    input[type="submit"]:hover,
    button[type="submit"]:hover {
      background: #43a047;
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
  <div class="container">
    <a href="../index.php" class="back-button">← Voltar para o site</a>

    <div id="alertMessage" class="alert"></div>
    <div id="loading" class="loading">
      <p>Enviando formulário... Por favor aguarde.</p>
    </div>

    <h1>Quero ser lar temporário!</h1>

    <form action="../includes/processar-lar-temporario.php" method="post" id="formularioLarTemporario">
      <div class="grupo">
        <h2>Nome completo</h2>
        <input
          type="text"
          name="nome"
          placeholder="Digite seu nome completo"
          required />
      </div>

      <div class="grupo">
        <h2>Telefone</h2>
        <input
          type="text"
          name="telefone"
          data-mask="telefone"
          placeholder="(11) 98765-4321"
          maxlength="15"
          required />
      </div>

      <div class="grupo">
        <h2>CPF</h2>
        <input
          type="text"
          name="cpf"
          data-mask="cpf"
          placeholder="123.456.789-00"
          maxlength="14"
          required />
      </div>

      <div class="grupo">
        <h2>Endereço</h2>
        <input
          type="text"
          name="endereco"
          placeholder="Digite seu endereço"
          required />
      </div>

      <div class="grupo">
        <h2>Por que decidiu se tornar lar temporário?</h2>
        <textarea
          name="mais_informacoes"
          rows="4"
          placeholder="Conte sua motivação para ser lar temporário"></textarea>
      </div>

      <div class="grupo">
        <h2>Espécies de interesse</h2>
        <div class="opcoes">
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

      <div class="grupo">
        <h2>Por quanto tempo poderia ser lar temporário?</h2>
        <input
          type="text"
          name="tempo_lar"
          placeholder="Digite sua disponibilidade" />
      </div>

      <div class="grupo">
        <h2>Pode medicar o animal, se necessário?</h2>
        <div class="opcoes">
          <input
            id="pode_medicar_sim"
            type="radio"
            name="pode_medicar"
            value="Sim" />
          <label for="pode_medicar_sim">Sim</label>
          <input
            id="pode_medicar_nao"
            type="radio"
            name="pode_medicar"
            value="Nao" />
          <label for="pode_medicar_nao">Não</label>
        </div>
      </div>

      <div class="grupo">
        <h2>Disponibilidade para medicações</h2>
        <div class="opcoes">
          <input
            id="horario_medicacao_1x"
            type="radio"
            name="horario_medicacao"
            value="Uma vez ao dia" />
          <label for="horario_medicacao_1x">1x ao dia</label>
          <input
            id="horario_medicacao_2x"
            type="radio"
            name="horario_medicacao"
            value="Duas vezes ao dia" />
          <label for="horario_medicacao_2x">2x ao dia</label>
          <input
            id="horario_medicacao_3x"
            type="radio"
            name="horario_medicacao"
            value="Tres vezes ao dia" />
          <label for="horario_medicacao_3x">3x ao dia</label>
        </div>
      </div>

      <div class="grupo">
        <h2>Experiência em medicação</h2>
        <div class="opcoes">
          <input
            id="experiencia_medicar_sim"
            type="radio"
            name="experiencia_medicar"
            value="Sim" />
          <label for="experiencia_medicar_sim">Sim</label>
          <input
            id="experiencia_medicar_nao"
            type="radio"
            name="experiencia_medicar"
            value="Nao" />
          <label for="experiencia_medicar_nao">Não</label>
        </div>
      </div>

      <div class="grupo">
        <h2>Experiência com filhotes órfãos</h2>
        <div class="opcoes">
          <input
            id="experiencia_filhote_sim"
            type="radio"
            name="experiencia_filhote"
            value="Sim" />
          <label for="experiencia_filhote_sim">Sim</label>
          <input
            id="experiencia_filhote_nao"
            type="radio"
            name="experiencia_filhote"
            value="Nao" />
          <label for="experiencia_filhote_nao">Não</label>
        </div>
      </div>

      <div class="grupo">
        <h2>Tipo de moradia</h2>
        <div class="opcoes">
          <input id="moradia_casa" type="radio" name="moradia" value="Casa" />
          <label for="moradia_casa">Casa</label>
          <input
            id="moradia_apartamento"
            type="radio"
            name="moradia"
            value="Apartamento" />
          <label for="moradia_apartamento">Apartamento</label>
        </div>
      </div>

      <div class="grupo">
        <h2>Possui abrigo adequado?</h2>
        <div class="opcoes">
          <input
            id="possui_abrigo_sim"
            type="radio"
            name="possui_abrigo"
            value="Sim" />
          <label for="possui_abrigo_sim">Sim</label>
          <input
            id="possui_abrigo_nao"
            type="radio"
            name="possui_abrigo"
            value="Nao" />
          <label for="possui_abrigo_nao">Não</label>
        </div>
      </div>

      <div class="grupo">
        <h2>Acesso limitado à rua</h2>
        <div class="opcoes">
          <input
            id="acesso_rua_grade"
            type="checkbox"
            name="acesso_rua"
            value="Grade" />
          <label for="acesso_rua_grade">Grade</label>
          <input
            id="acesso_rua_muro"
            type="checkbox"
            name="acesso_rua"
            value="Muro" />
          <label for="acesso_rua_muro">Muro</label>
          <input
            id="acesso_rua_cerca"
            type="checkbox"
            name="acesso_rua"
            value="Cerca eletrica" />
          <label for="acesso_rua_cerca">Cerca elétrica</label>
          <input
            id="acesso_rua_tela"
            type="checkbox"
            name="acesso_rua"
            value="Tela de protecao" />
          <label for="acesso_rua_tela">Tela de proteção</label>
        </div>
      </div>

      <div class="grupo">
        <h3>Possui outros animais?</h3>
        <div class="opcoes">
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
      </div>

      <div class="grupo">
        <input
          type="text"
          name="detalhes_animais"
          placeholder="Se sim, quantos?" />
        <input
          class="spacing-top"
          type="text"
          name="cachorro"
          placeholder="Quantidade de cachorros" />
        <input
          class="spacing-top"
          type="text"
          name="cachorro_castrado"
          placeholder="Cachorros castrados?" />
        <input
          class="spacing-top"
          type="text"
          name="gato"
          placeholder="Quantidade de gatos" />
        <input
          class="spacing-top"
          type="text"
          name="gato_castrado"
          placeholder="Gatos castrados?" />
      </div>

      <div class="grupo">
        <h2>FIV/FELV</h2>
        <div class="opcoes">
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

      <input type="submit" value="Enviar" />
    </form>
  </div>

  <script src="../public/js/formulario-handler.js"></script>
  <script>
    // Inicializa o formulário de lar temporário
    inicializarFormularioAjax('formularioLarTemporario', '../includes/processar-lar-temporario.php');
  </script>

  <!-- Script de máscaras para telefone e CPF -->
  <script src="../public/js/mascaras.js"></script>
</body>

</html>