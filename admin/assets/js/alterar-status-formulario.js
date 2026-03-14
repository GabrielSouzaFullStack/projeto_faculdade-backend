/**
 * Controle de alteração de status de formulários
 * Usado em formulários de adoção e lar temporário
 */

/**
 * Inicializa o controle de status para um formulário
 * @param {number} formularioId - ID do formulário
 * @param {string} tipoFormulario - Tipo: 'adocao' ou 'lar_temporario'
 */
function inicializarControleStatus(formularioId, tipoFormulario) {
  const statusButtons = document.querySelectorAll(".status-btn");
  const alertSuccess = document.getElementById("alertSuccess");
  const alertError = document.getElementById("alertError");

  if (!statusButtons.length) {
    console.warn("Nenhum botão de status encontrado");
    return;
  }

  statusButtons.forEach((button) => {
    button.addEventListener("click", async () => {
      const novoStatus = button.dataset.status;

      // Confirma alteração
      const statusTexto = button.textContent.trim();
      if (
        !confirm(
          `Tem certeza que deseja alterar o status para "${statusTexto}"?`,
        )
      ) {
        return;
      }

      // Desabilita botões durante a requisição
      statusButtons.forEach((btn) => (btn.disabled = true));

      try {
        const response = await fetch(
          "controllers/alterar-status-formulario.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              id: formularioId,
              tipo: tipoFormulario,
              status: novoStatus,
            }),
          },
        );

        const result = await response.json();

        if (result.success) {
          // Remove active de todos
          statusButtons.forEach((btn) => btn.classList.remove("active"));
          // Adiciona active no clicado
          button.classList.add("active");

          // Mostra mensagem de sucesso
          alertSuccess.textContent = result.message;
          alertSuccess.style.display = "block";
          alertError.style.display = "none";

          // Esconde após 5 segundos
          setTimeout(() => {
            alertSuccess.style.display = "none";
          }, 5000);

          // Recarrega a página para atualizar o badge
          setTimeout(() => {
            location.reload();
          }, 1500);
        } else {
          alertError.textContent = result.message;
          alertError.style.display = "block";
          alertSuccess.style.display = "none";
        }
      } catch (error) {
        console.error("Erro ao alterar status:", error);
        alertError.textContent = "Erro ao alterar status. Tente novamente.";
        alertError.style.display = "block";
        alertSuccess.style.display = "none";
      } finally {
        // Reabilita botões
        statusButtons.forEach((btn) => (btn.disabled = false));
      }
    });
  });
}
