/**
 * Quando clicar no botão 'id="passToggle"', se ele existe, chama a função 
 * 'togglePassword'.
 */
if (!!document.getElementById("passToggle")) passToggle.onclick = togglePassword;
if (!!document.getElementById("newPassToggle")) newPassToggle.onclick = togglePassword;

/**
 * Função que mostra/oculta (toggle) o valor do campo 'senha do usuário'.
 */
function togglePassword() {

    // Obtém o id do campo 'alvo'.
    idField = this.getAttribute('data-field');

    // Se o campo é do tipo 'password'...
    if (document.getElementById(idField).type == 'password') {

        // ... troca para o tipo 'text'.
        document.getElementById(idField).type = 'text';

        // ... troca o ícone do botão.
        this.innerHTML = '<i class="fa-solid fa-eye-slash fa-fw"></i>';

        // Se o campo é do tipo 'text'...
    } else {

        // ... troca para o tipo 'password'.
        document.getElementById(idField).type = 'password';

        // ... troca o ícone do botão.
        this.innerHTML = '<i class="fa-solid fa-eye fa-fw"></i>';

    }

    // Sai da função sem fazer mais nada.
    return false;
}