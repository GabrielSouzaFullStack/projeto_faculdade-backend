/**
 * Handler genérico para submissão de formulários via AJAX
 * Gerencia loading, alertas e resetar formulário
 */

/**
 * Inicializa handler de formulário com submissão AJAX
 * @param {string} formId - ID do formulário
 * @param {string} submitUrl - URL para submeter o formulário
 * @param {Object} options - Opções customizáveis
 */
function inicializarFormularioAjax(formId, submitUrl, options = {}) {
  const form = document.getElementById(formId);

  if (!form) {
    console.error(`Formulário #${formId} não encontrado`);
    return;
  }

  const defaultOptions = {
    loadingId: "loading",
    alertId: "alertMessage",
    resetOnSuccess: true,
    resetDelay: 2000,
    scrollToTop: true,
    disableButton: true,
    successCallback: null,
    errorCallback: null,
  };

  const config = { ...defaultOptions, ...options };

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    console.log("Formulário enviado:", formId);

    const loading = document.getElementById(config.loadingId);
    const alert = document.getElementById(config.alertId);
    const submitButton = form.querySelector(
      'input[type="submit"], button[type="submit"]',
    );

    // Validação dos elementos necessários
    if (!loading || !alert) {
      console.error("Elementos de loading ou alerta não encontrados");
      window.alert(
        "Erro: Elementos da página não encontrados. Recarregue a página.",
      );
      return;
    }

    // Mostra loading e esconde alertas
    loading.style.display = "block";
    loading.classList.add("show");
    alert.style.display = "none";
    alert.classList.remove("show");

    // Desabilita botão se configurado
    if (config.disableButton && submitButton) {
      submitButton.disabled = true;
    }

    try {
      const formData = new FormData(form);
      console.log("Enviando dados para:", submitUrl);

      const response = await fetch(submitUrl, {
        method: "POST",
        body: formData,
      });

      console.log("Status da resposta:", response.status);

      if (!response.ok) {
        throw new Error(`Erro HTTP: ${response.status}`);
      }

      const result = await response.json();
      console.log("Resposta:", result);

      // Esconde loading
      loading.style.display = "none";
      loading.classList.remove("show");

      // Reabilita botão
      if (config.disableButton && submitButton) {
        submitButton.disabled = false;
      }

      if (result.success) {
        // Sucesso
        alert.className = "alert alert-success show";
        alert.textContent = result.message;
        alert.style.display = "block";

        // Scroll para o topo se configurado
        if (config.scrollToTop) {
          window.scrollTo({
            top: 0,
            behavior: "smooth",
          });
        }

        // Reseta formulário se configurado
        if (config.resetOnSuccess) {
          setTimeout(() => {
            form.reset();
          }, config.resetDelay);
        }

        // Callback de sucesso
        if (
          config.successCallback &&
          typeof config.successCallback === "function"
        ) {
          config.successCallback(result);
        }

        // Esconde alerta após 5 segundos
        setTimeout(() => {
          alert.style.display = "none";
          alert.classList.remove("show");
        }, 5000);
      } else {
        // Erro retornado pela API
        alert.className = "alert alert-error show";
        alert.textContent = result.message || "Erro ao processar formulário";
        alert.style.display = "block";

        if (config.scrollToTop) {
          window.scrollTo({
            top: 0,
            behavior: "smooth",
          });
        }

        // Callback de erro
        if (
          config.errorCallback &&
          typeof config.errorCallback === "function"
        ) {
          config.errorCallback(result);
        }
      }
    } catch (error) {
      console.error("Erro ao enviar formulário:", error);

      // Esconde loading
      loading.style.display = "none";
      loading.classList.remove("show");

      // Reabilita botão
      if (config.disableButton && submitButton) {
        submitButton.disabled = false;
      }

      // Mostra erro
      alert.className = "alert alert-error show";
      alert.textContent = "Erro ao enviar o formulário: " + error.message;
      alert.style.display = "block";

      if (config.scrollToTop) {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      }

      // Callback de erro
      if (config.errorCallback && typeof config.errorCallback === "function") {
        config.errorCallback({ success: false, message: error.message });
      }
    }
  });
}
