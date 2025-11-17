# Sobre o Projeto: Chatbot_braga_organizado

Este projeto consiste em um tema filho para WordPress (baseado no Hello Elementor) que implementa um chatbot avançado de Inteligência Artificial, chamado "BIA". O chatbot é projetado para atuar como um assistente virtual para o site "Braga Designs Web", fornecendo informações sobre serviços, produtos e a empresa.

A solução utiliza uma abordagem de Geração Aumentada por Recuperação (RAG), onde a IA (Google Gemini) não apenas usa um prompt pré-definido, mas também enriquece suas respostas com informações extraídas em tempo real de páginas específicas do WordPress e do catálogo de produtos do WooCommerce.

Além do chatbot, o projeto inclui uma funcionalidade customizada para melhorar a experiência do usuário ao encontrar erros de limite de download no WooCommerce, acionando um pop-up do Elementor Pro para oferecer assistência.

## 🚀 Funcionalidades Principais

*   **Integração com IA Generativa:** Conecta-se à API do Google Gemini para gerar respostas inteligentes e contextuais.
*   **Contexto Dinâmico (RAG):** Extrai conteúdo de páginas específicas do WordPress e realiza buscas em produtos do WooCommerce para fundamentar as respostas da IA.
*   **Interface de Chat Completa:** Implementa um widget de chat flutuante com ícone de lançamento, janela de conversa, avatares e indicador de "digitando".
*   **Memória de Conversa:** Mantém um histórico da conversa atual por sessão, permitindo que a IA entenda o contexto do diálogo em andamento.
*   **Comunicação AJAX:** Utiliza o sistema AJAX nativo do WordPress para uma comunicação assíncrona e eficiente entre o front-end e o back-end.
*   **Abertura Automática Inteligente:** O chat abre automaticamente após 10 segundos em dispositivos desktop, mas respeita a decisão do usuário se ele o fechar manualmente.
*   **Experiência de Resposta Natural:** Apresenta as respostas da IA em blocos de mensagens sequenciais, simulando um fluxo de conversa mais humano.
*   **Gestão de Erros de Download:** Intercepta o erro de limite de download do WooCommerce, redireciona o usuário e aciona um pop-up específico do Elementor Pro.
*   **Design Responsivo:** A interface do chat se adapta para uma visualização otimizada em dispositivos móveis.

## 📂 Visão Geral dos Módulos/Arquivos

*   `functions.php`: O núcleo lógico do tema. Responsável por:
    *   Enfileirar os arquivos de estilo (`style.css`) e scripts (`chatbot-rag.js`, `pop-up.js`).
    *   Criar o endpoint AJAX (`handle_chatbot_query`) que recebe as mensagens do usuário.
    *   Definir o prompt principal, a personalidade da "BIA" e o contexto da empresa (`get_company_context`).
    *   Gerenciar o histórico de conversa usando sessões e `transients`.
    *   Consultar a API do Google Gemini (`call_gemini_api`).
    *   Implementar a função de busca de produtos no WooCommerce (`search_products_by_keyword`).
    *   Controlar o redirecionamento para o pop-up em caso de erro de download (`braga_force_download_limit_redirect`).

*   `footer.php`: Insere a estrutura HTML básica do ícone do chat e da janela de conversa no rodapé de todas as páginas do site.

*   `style.css`: Contém todas as regras de estilização para a interface do chatbot, incluindo o ícone de lançamento, a janela de chat, os balões de mensagem, avatares, o campo de input e as regras de responsividade para telas menores.

*   `js/chatbot-rag.js`: Controla todo o comportamento do chatbot no lado do cliente. Suas funções incluem:
    *   Gerenciar a abertura e o fechamento da janela do chat.
    *   Executar a lógica de abertura automática em desktops.
    *   Capturar o envio do formulário, enviar a mensagem do usuário para o back-end via `fetch` e exibir a resposta da IA.
    *   Renderizar as mensagens na tela, incluindo o indicador "digitando" para melhorar a UX.

*   `js/pop-up.js`: Script específico para a funcionalidade de erro de download. Ele verifica se a URL contém o parâmetro `download_error=limit_exceeded` e, em caso afirmativo, aciona um pop-up específico do Elementor Pro.

## ⚙️ Como Usar

Este projeto é um tema filho para WordPress e depende de um ambiente com plugins específicos.

**Pré-requisitos:**
*   WordPress instalado.
*   Tema **Hello Elementor** instalado e ativo.
*   Plugin **WooCommerce** instalado e ativo (para a busca de produtos).
*   Plugin **Elementor Pro** instalado e ativo (para a funcionalidade de pop-up).

**Instalação:**
1.  Faça o upload da pasta do projeto `Chatbot_braga_organizado` para o diretório `/wp-content/themes/` da sua instalação WordPress.
2.  No painel do WordPress, vá em `Aparência > Temas` e ative o tema filho "Hello Elementor Child".

**Configuração Essencial:**
1.  **Chave da API Gemini:** Edite o arquivo `functions.php`. Na função `call_gemini_api()`, substitua o placeholder `[CENSORED_GEMINI_API_KEY]` pela sua chave de API válida do Google Gemini.
2.  **ID do Pop-up:** Edite o arquivo `js/pop-up.js`. Na linha `const popupId = 1349;`, substitua `1349` pelo ID real do seu pop-up criado no Elementor.
3.  **Contexto da IA:** A IA busca conteúdo das páginas com os slugs `a-historia` e `loja-braga-pro-tools`. Certifique-se de que estas páginas existam ou altere os slugs na função `get_company_context()` em `functions.php` para corresponder às suas páginas de conteúdo.