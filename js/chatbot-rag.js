document.addEventListener('DOMContentLoaded', function () {
    // --- Elementos da Interface ---
    const launcher = document.getElementById('chat-launcher');
    const chatWindow = document.getElementById('meu-chat');
    const closeBtn = document.getElementById('chat-close-btn');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const chatBody = document.getElementById('chat-body');

    // --- Funções de Controle da Janela ---
    // Criamos funções separadas para abrir e fechar para não repetir código.
    
    function openChat() {
        // Mostra a janela do chat e esconde o ícone
        chatWindow.classList.add('chat-visible');
        chatWindow.classList.remove('chat-hidden');
        launcher.classList.add('launcher-hidden');
    }

    function closeChat() {
        // Esconde a janela do chat e mostra o ícone
        chatWindow.classList.add('chat-hidden');
        chatWindow.classList.remove('chat-visible');
        launcher.classList.remove('launcher-hidden');
    }


    // --- NOVA LÓGICA DE AUTO-ABERTURA ---
    
    // --- CÓDIGO FINAL - SÓ SUBSTITUIR UM PELO OUTRO ---

    // Verifica se a tela é de desktop ANTES de sequer criar o timer.
    if (window.innerWidth > 768) {

        // Timer para abrir o chat após 10 segundos.
        setTimeout(() => {
            // A sua lógica inteligente de sessionStorage continua aqui dentro, intacta.
            if (sessionStorage.getItem('chatClosedByUser') !== 'true') {
                openChat();
            }
        }, 10000); // 10000 milissegundos = 10 segundos
    }


    // --- Lógica para Abrir e Fechar o Chat (com cliques) ---
    if (launcher && chatWindow && closeBtn) {
        launcher.addEventListener('click', openChat);

        closeBtn.addEventListener('click', function() {
            closeChat();
            // Quando o usuário fecha manualmente, a gente anota na memória da sessão.
            sessionStorage.setItem('chatClosedByUser', 'true');
        });
    }


    // --- Lógica de Envio de Mensagem (VERSÃO COM RESPOSTAS PAUSADAS) ---
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const userMessage = input.value.trim();

            if (userMessage === '') return;

            addMessage(userMessage, 'user');
            input.value = '';
            showTypingIndicator(); // Mostra "digitando..." inicial

            fetch(chatbot_ajax_object.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: new URLSearchParams({
                    action: 'handle_chatbot_query',
                    nonce: chatbot_ajax_object.nonce,
                    message: userMessage
                })
            })
            .then(response => response.json())
            .then(data => {
                removeTypingIndicator(); // Remove o "digitando..." inicial
                
                if (data.success) {
                    // Pega a resposta completa e quebra em parágrafos
                    const fullReply = data.data.reply;
                    const messages = fullReply.split('\n').filter(msg => msg.trim() !== '');
                    
                    // Envia as mensagens em sequência, com pausas
                    sendMessagesSequentially(messages);

                } else {
                    addMessage('Putz, deu algum B.O. aqui. Tente de novo mais tarde.', 'bot');
                    console.error('Erro na API:', data.data.message);
                }
            })
            .catch(error => {
                removeTypingIndicator();
                addMessage('Erro de conexão. Minha bola de cristal tá fora do ar.', 'bot');
                console.error('Erro no Fetch:', error);
            });
        });
    }


    // --- Funções Auxiliares de Mensagem (VERSÃO ATUALIZADA) ---

    // Função para enviar mensagens em sequência com pausas
    function sendMessagesSequentially(messagesArray) {
        if (messagesArray.length === 0) return; // Terminou de enviar

        // Adiciona um tempo de "pensamento" antes de responder
        setTimeout(() => {
            const messageToSend = messagesArray.shift(); // Pega a primeira mensagem da fila
            addMessage(messageToSend, 'bot');

            // Se ainda houver mensagens na fila, mostra o "digitando..." de novo
            if (messagesArray.length > 0) {
                showTypingIndicator();
                // Chama a si mesma para enviar a próxima mensagem depois de uma pausa
                setTimeout(() => {
                    removeTypingIndicator();
                    sendMessagesSequentially(messagesArray); // Recursão
                }, 3000); // Pausa de 2 segundos entre as mensagens
            }

        }, 2500); // Pausa de 1.5 segundos antes da primeira (ou próxima) resposta
    }
    
    function addMessage(text, type) {
        const messageContainer = document.createElement('div');
        messageContainer.classList.add('chat-message', type);

        if (type === 'bot') {
            messageContainer.innerHTML = `
                <div class="avatar">
                    <img src="${chatbot_ajax_object.bia_avatar_url}" alt="BIA">
                </div>
                <div class="message-content">
                    <span></span>
                </div>
            `;
            // Usamos innerText aqui para evitar problemas com HTML na resposta da IA
            messageContainer.querySelector('.message-content span').innerText = text;
        } else {
            messageContainer.innerHTML = `<div class="message-content"><span></span></div>`;
            messageContainer.querySelector('.message-content span').innerText = text;
        }

        chatBody.appendChild(messageContainer);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    function showTypingIndicator() {
        // Evita adicionar vários indicadores de "digitando"
        if (document.getElementById('typing-indicator')) return;

        const typingElement = document.createElement('div');
        typingElement.id = 'typing-indicator';
        typingElement.classList.add('chat-message', 'bot');
        typingElement.innerHTML = `
            <div class="avatar">
                <img src="${chatbot_ajax_object.bia_avatar_url}" alt="BIA">
            </div>
            <div class="message-content">
                <span class="typing-dots"><span>.</span><span>.</span><span>.</span></span>
            </div>
        `;
        chatBody.appendChild(typingElement);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.remove();
        }
    }
});

/**
 * Script para corrigir o alinhamento da caixa .woocommerce-info
 * Envelopa o texto solto em uma <span> para o flexbox funcionar.
 */
document.addEventListener('DOMContentLoaded', function() {
    const infoBoxes = document.querySelectorAll('.woocommerce-MyAccount-content .woocommerce-info');
    infoBoxes.forEach(box => {
        // Encontra apenas os nós de texto que não são só espaços em branco
        const textNodes = Array.from(box.childNodes).filter(node => 
            node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0
        );

        textNodes.forEach(textNode => {
            const spanWrapper = document.createElement('span');
            spanWrapper.textContent = textNode.textContent;
            textNode.parentNode.replaceChild(spanWrapper, textNode);
        });
    });
});