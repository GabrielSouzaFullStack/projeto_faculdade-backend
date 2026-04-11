/**
 * Máscaras de formatação para formulários
 * Aplica formatação automática em campos de telefone e CPF
 */

/**
 * Aplica máscara de telefone em tempo real
 * Formato: (11) 98765-4321 ou (11) 8765-4321
 * @param {HTMLInputElement} input - Campo de input do telefone
 */
function aplicarMascaraTelefone(input) {
  input.addEventListener("input", function (e) {
    let valor = e.target.value.replace(/\D/g, ""); // Remove tudo que não é dígito

    // Limita a 11 dígitos
    if (valor.length > 11) {
      valor = valor.substring(0, 11);
    }

    // Aplica a formatação
    if (valor.length <= 10) {
      // Formato: (11) 8765-4321
      valor = valor.replace(
        /^(\d{2})(\d{0,4})(\d{0,4}).*/,
        function (match, ddd, parte1, parte2) {
          let resultado = "";
          if (ddd) resultado = "(" + ddd;
          if (parte1) resultado += ") " + parte1;
          if (parte2) resultado += "-" + parte2;
          return resultado;
        },
      );
    } else {
      // Formato: (11) 98765-4321
      valor = valor.replace(
        /^(\d{2})(\d{5})(\d{0,4}).*/,
        function (match, ddd, parte1, parte2) {
          let resultado = "(" + ddd + ") " + parte1;
          if (parte2) resultado += "-" + parte2;
          return resultado;
        },
      );
    }

    e.target.value = valor;
  });

  // Permite apenas números e caracteres de formatação
  input.addEventListener("keypress", function (e) {
    const char = String.fromCharCode(e.which);
    if (!/[0-9]/.test(char)) {
      e.preventDefault();
    }
  });
}

/**
 * Aplica máscara de CPF em tempo real
 * Formato: 123.456.789-00
 * @param {HTMLInputElement} input - Campo de input do CPF
 */
function aplicarMascaraCPF(input) {
  input.addEventListener("input", function (e) {
    let valor = e.target.value.replace(/\D/g, ""); // Remove tudo que não é dígito

    // Limita a 11 dígitos
    if (valor.length > 11) {
      valor = valor.substring(0, 11);
    }

    // Aplica a formatação: 123.456.789-00
    if (valor.length > 9) {
      valor = valor.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})$/, "$1.$2.$3-$4");
    } else if (valor.length > 6) {
      valor = valor.replace(/^(\d{3})(\d{3})(\d{0,3})$/, "$1.$2.$3");
    } else if (valor.length > 3) {
      valor = valor.replace(/^(\d{3})(\d{0,3})$/, "$1.$2");
    }

    e.target.value = valor;
  });

  // Permite apenas números
  input.addEventListener("keypress", function (e) {
    const char = String.fromCharCode(e.which);
    if (!/[0-9]/.test(char)) {
      e.preventDefault();
    }
  });
}

/**
 * Inicializa as máscaras automaticamente
 * Busca campos com atributos data-mask e aplica a máscara correspondente
 */
function inicializarMascaras() {
  // Aplica máscara de telefone em campos com data-mask="telefone"
  document
    .querySelectorAll('input[data-mask="telefone"]')
    .forEach(function (input) {
      aplicarMascaraTelefone(input);
    });

  // Aplica máscara de CPF em campos com data-mask="cpf"
  document.querySelectorAll('input[data-mask="cpf"]').forEach(function (input) {
    aplicarMascaraCPF(input);
  });

  // Também aplica por name ou id (fallback)
  const camposTelefone = document.querySelectorAll(
    'input[name="telefone"], input[id*="telefone"], input[name*="tel"]',
  );
  camposTelefone.forEach(function (input) {
    if (!input.hasAttribute("data-mask")) {
      aplicarMascaraTelefone(input);
    }
  });

  const camposCPF = document.querySelectorAll(
    'input[name="cpf"], input[id*="cpf"]',
  );
  camposCPF.forEach(function (input) {
    if (!input.hasAttribute("data-mask")) {
      aplicarMascaraCPF(input);
    }
  });
}

// Inicializa as máscaras quando o DOM estiver pronto
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", inicializarMascaras);
} else {
  inicializarMascaras();
}
