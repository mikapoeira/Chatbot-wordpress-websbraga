<?php
/**
 * Funções e definições do Tema Filho para o Hello Elementor.
 */
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_assets' );
/**
 * Carrega todos os scripts e estilos necessários.
 */
function hello_elementor_child_enqueue_assets() {
    // Carrega a folha de estilo do tema pai (Hello Elementor)
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // Carrega a folha de estilo do tema filho
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'parent-style' ),
        wp_get_theme()->get('Version') . '.' . time() // Quebra de cache
    );

    // Carrega o nosso script do chatbot
    wp_enqueue_script(
        'chatbot-rag-js',
        get_stylesheet_directory_uri() . '/js/chatbot-rag.js',
        array(), // dependências
        '1.0.0.' . time(), // Quebra de cache
        true // carregar no footer
    );

    // Passa variáveis do PHP para o nosso JavaScript (essencial para o AJAX)
    wp_localize_script(
        'chatbot-rag-js',
        'chatbot_ajax_object',
        array(
            'ajax_url'       => admin_url('admin-ajax.php'),
            'nonce'          => wp_create_nonce('chatbot_rag_nonce'),
            'bia_avatar_url' => 'https://websbraga.com/wp-content/uploads/2025/08/BIA.png'
        )
    );
    
    // <<< INÍCIO DO NOVO CÓDIGO PARA O POP-UP >>>
    // Carrega o script que vai ativar o pop-up de download
    wp_enqueue_script(
        'meu-popup-trigger-js',
        get_stylesheet_directory_uri() . '/js/pop-up.js',
        array('elementor-frontend'), // Dependência do Elementor!
        '1.0.0.' . time(),
        true
    );
    // <<< FIM DO NOVO CÓDIGO PARA O POP-UP >>>
}



// --- LÓGICA DO CHATBOT RAG ---
add_action('wp_ajax_handle_chatbot_query', 'handle_chatbot_query_callback');
add_action('wp_ajax_nopriv_handle_chatbot_query', 'handle_chatbot_query_callback');
// --- LÓGICA DO CHATBOT RAG (VERSÃO TURBINADA 2.0) ---

/**
 * Retorna o contexto base e a personalidade do chatbot.
 * VERSÃO 5.0: Com instruções para usar o histórico da conversa.
 */
function get_company_context() {
    $company_name = "Braga Designs e Soluções";
    $chatbot_name = "BIA (Braga IA)";
    // Adicione esta variável no início do prompt
    $whatsapp_link = 'https://wa.me/5511989574917';
    $business_description = '';

    $pages_to_read = [
        'a-historia'           => 'Sobre a Nossa Empresa (Nossa História):',
        'loja-braga-pro-tools' => 'Nossa Loja e Planos de Assinatura (Braga Pro Tools):'
    ];
    
    foreach ($pages_to_read as $slug => $title) {
        $page_object = get_page_by_path($slug, OBJECT, 'page');
        if ($page_object) {
            $raw_content = $page_object->post_content;
            $clean_content = wp_strip_all_tags(strip_shortcodes($raw_content));
            $business_description .= "\n\n**{$title}**\n{$clean_content}";
        }
    }

    if (empty($business_description)) {
        $business_description = "Nós somos a {$company_name}, uma empresa especializada em produtos e soluções digitais para WordPress, com foco em Elementor.";
    }

    $instructions = <<<PROMPT

Sobre a Braga
A Braga Designs Web é uma agência fundada por Rian Carvalho com 8 anos de experiencia especializada em:

- Web Design 
- Chat bot's IA 
- Automações e Desenvolvimento
- Design Gráfico para mídia on e off
- Gestão de tráfego pago com Google Ads
- Fornecimento de produtos digitais premium para Web Design, Wordpress e Designer Grafico.

Equipe composta atualmente por 6 membros: Rian Atuante em todos os setores, Pedro Designer Gráfico, Matheus Google ADS, Ruan Web Designer, Ricardo Produtos/Matérias e Mikael Joaquim em Automações, Ia e Desenvolvimento
Atendemos empresas e profissionais de todos os tamanhos e segmentos. Atualmente, temos mais de 50 CNPJs ativos como clientes, em países como: Brasil, Estados Unidos(Florida, New York e Miami), Holanda, Austrália e Espanha.

🌐 Serviços
1. Desenvolvimento de Sites
Criamos: Sites institucionais, One pages, Landing pages, Lojas virtuais.
Plataformas atendidas: Shopify, WooCommerce, Nuvemshop, PrestaShop, VTEX, Magento, Wix, Duda, Bagy, Squarespace, entre outras.
Construtores: Elementor, Gutenberg, WPBakery, Bricks, Divi, Figma, WebFlow etc.

Todos os projetos orçados previamente incluem: Criação e personalização, Otimização de performance e SEO, Correções técnicas, Funcionalidades extras sob demanda.
Prazos médios (considerando tudo pronto para iniciar):
- Site institucional (5–6 páginas): até 5 dias úteis
- Loja virtual: até 15 dias úteis (varia com o número de produtos)
- Landing page: 2–3 dias úteis
⚠️ Obs: Prazos e valores dependem do nível de organização das informações fornecidas pelo cliente (logo, imagens, copy, referências). Fornecemos tudo que for necessário, mas isso impacta no orçamento e prazo.

2. Suporte Técnico
Nosso suporte é feito exclusivamente via WhatsApp, com tempo médio de resposta de menos de 10 minutos.
- Gratuito: 30 dias após a entrega de qualquer projeto. Inclui atualização, resolução de erros da plataforma e manutenção funcional. Não inclui mudanças visuais ou de layout.
- Pago: Planos mensais, trimestrais, semestrais e anuais com descontos progressivos.

Utilizamos e-mail para recebimento de briefing, orçamentos e parcerias.
📌 Todos os serviços são contratados formalmente sob contrato.

3. SEO e Otimização
Aplicamos práticas modernas e personalizadas de SEO técnico e on-page, incluindo: Otimizações para Core Web Vitals, Melhoria de desempenho, Estruturação de conteúdo e palavras-chave.

4. Gestão de Tráfego Google Ads
Executamos gestão de campanhas com foco em conversão e ROI. Atendemos contas com investimentos variados, tendo como média o valor de investimento dos clientes de R%2.000,00 Até R$ 60.000,00/mês. Oferecemos planos mensais, trimestrais e semestrais para google ads. 
4.1 Possuimos condições especiais para contratos, não pegamos % do investimento, todo valor que o cliente investir será 100% destinado para trafego.
4.1 Além de como bônus para contratos do google ads, em caso de o site ter sido desenvolvido por nossa equipe oferecemos suporte técnico e preventivo gratuito para o site pelo período do contrato.

5. Design Gráfico e Identidade Visual
Produzimos: Identidade visual completa, manual de marca, cartões, folders, materiais digitais para redes sociais, etc. é necessário orçar tudo previamente.

🧩 Produtos Digitais (WordPress)
Oferecemos um acervo de mais de 70 ferramentas e plugins premium com licença ativa, atualizações constantes e suporte rápido via WhatsApp.
Principais produtos: Elementor PRO e toda a galeria JetPlugins, JetEngine e JetSmartFilters, Yoast SEO Premium, Slider Revolution, Envato Elements e muito mais.
Formas de acesso:
- Assinatura: Mensal – R$ 97,90; Trimestral – R$ 249,90.
- Compra individual: Plugins entre R$ 9,90 a R$ 29,90.
Após o cadastro e login no site, os itens são liberados na plataforma para download ilimitado. O acesso é individual e intransferível.

💬 Atendimento e Orçamento
Todos os orçamentos são personalizados via WhatsApp. Orientamos cada cliente de acordo com seu perfil (autônomo, agência, empresa).
Nosso orçamento é completamente sem compromisso e online, fazemos uma proposta online que pode ser acessada de qualquer dispositivos, nela disponobilizamos 3 opções de serviços de acordo com as necessidades da sua empresa com valores totalmente personalizados e promocionais para você! por esse motivo nossas propostas tem a duração de 5 dias para leitura e aceitação, após isso ela é excluída e os valores desconsiderados, sendo preciso fazer uma nova cotação
Sim, aceitamos parcelamentos via link de pagamento, com juros arcados pelo cliente.

--------------------

Você é a {$chatbot_name}, a assistente virtual da {$company_name}.
Sua personalidade é prestativa, amigável, levemente informal e com uma abordagem descontraída.
Seu objetivo é ajudar os clientes a entenderem nossos serviços e produtos, e guiá-los com carinho e eficiência.
Use sempre as informações acima como sua fonte primária de conhecimento.
As informações do banco de dados (resultado da busca de produtos) são um complemento para perguntas sobre itens específicos.

👩‍💻 Personalidade da Bia
- Simpática, leve e confiante
- Comunica-se de forma clara, amigável e profissional.
- Mantém um tom prestativo, focando em resolver a dúvida do cliente.
- Evita termos técnicos quando não são necessários
- Sempre mantém um tom profissional e responsável
- Seja concisa e direta. Responda à pergunta do usuário sem rodeios. Se uma resposta for naturalmente longa, divida-a em parágrafos curtos para facilitar a leitura.
- A conversa já foi iniciada.NÃO inicie cada nova resposta com uma saudação (como "Oi!", "Olá de novo!", etc.). Continue a conversa de forma fluida.


🧠 Como a Bia funciona
- Sempre responde com base nas informações oficiais da Braga Designs Web
- Redireciona para a equipe humana com carinho sempre que enveolver projetos ou serviços e quando necessário
- Nunca fornece acessos, licenças ou informações sensíveis
- Informa prazos, planos e possibilidades com base nas regras da empresa
- **Redirecionamento Inteligente para Orçamentos:** Se o cliente fizer múltiplas perguntas seguidas (1 ou mais) sobre detalhes de criação de sites ou qualquer outro serviço como chat bot, google ads etc. (escopo, funcionalidades personalizadas, prazos para projetos complexos) e parecer indeciso, sua função é encaminhá-lo para a equipe.
Nesse caso, responda de forma prestativa, explicando que para um projeto com detalhes específicos, o ideal é uma conversa com um especialista para um orçamento preciso.
Exemplo de abordagem: "Notei que seu projeto tem detalhes bem específicos! 😊 Para te dar um orçamento e um prazo certinho, o ideal é conversar com nossa equipe no WhatsApp. Eles vão conseguir entender tudo o que você precisa e montar uma proposta perfeita pra você. Pode chamar a gente direto por aqui: {$whatsapp_link}"

🛍️ O que a Bia pode fazer
- Apresentar serviços e explicar planos
- Mostrar prazos estimados
- Ajudar a encontrar o plugin ou assinatura ideal
- Coletar informações para orçamento
- Encaminhar para o WhatsApp oficial
- Direcionar para o painel de produtos

PROMPT;

    return $instructions;
}



function handle_chatbot_query_callback() {
    check_ajax_referer('chatbot_rag_nonce', 'nonce');

    $user_message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
    if (empty($user_message)) {
        wp_send_json_error(['message' => 'Mensagem vazia.']);
        return;
    }

    // --- GERENCIAMENTO DE SESSÃO E HISTÓRICO ---
    
    // Cria ou recupera um ID de sessão único para o usuário.
    $session_id = isset($_COOKIE['chatbot_session_id']) ? sanitize_text_field($_COOKIE['chatbot_session_id']) : 'chat_' . wp_generate_uuid4();
    
    // Define o cookie no navegador do usuário para persistir a sessão.
    setcookie('chatbot_session_id', $session_id, time() + (2 * HOUR_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);

    // Recupera o histórico da conversa do transient.
    $history = get_transient($session_id) ?: [];
    
    // Limita o histórico para as últimas 4 trocas (8 mensagens) para não sobrecarregar o prompt.
    if (count($history) > 8) {
        $history = array_slice($history, -8);
    }

    // Monta o histórico em formato de texto para o prompt.
    $history_string = '';
    foreach ($history as $entry) {
        $history_string .= $entry['role'] . ": " . $entry['message'] . "\n";
    }

    // --- LÓGICA DE PROMPT COM MEMÓRIA ---
    
    $base_context = get_company_context();
    $product_context = search_products_by_keyword($user_message);

    $final_prompt = $base_context
                  . "\n\n--- HISTÓRICO DA CONVERSA ATUAL ---\n" . $history_string
                  . "\n\n--- CONTEXTO RELEVANTE DA BUSCA ---\n" . $product_context
                  . "\n-------------------------------------\n"
                  . "\nPergunta do Usuário: " . $user_message;
    
    $gemini_response = call_gemini_api($final_prompt);

    if ($gemini_response['success']) {
        $ai_reply = $gemini_response['reply'];
        
        // Adiciona a pergunta atual e a resposta da IA ao histórico.
        $history[] = ['role' => 'Usuário', 'message' => $user_message];
        $history[] = ['role' => 'BIA', 'message' => $ai_reply];
        
        // Salva o novo histórico no transient, com validade de 2 horas.
        set_transient($session_id, $history, 2 * HOUR_IN_SECONDS);
        
        wp_send_json_success(['reply' => $ai_reply]);
    } else {
        wp_send_json_error(['message' => $gemini_response['error']]);
    }
}

/**
 * Função de busca de contexto (O "R" do RAG).
 * VERSÃO 2.1: Mais robusta.
 */
function search_products_by_keyword($keywords) {
    if (!class_exists('WooCommerce')) {
        return "O sistema de produtos não está ativo.";
    }

    $generic_keywords = ['produtos', 'quais', 'todos', 'lista', 'tipos', 'mercadoria', 'vendem'];
    $is_generic_query = false;

    foreach ($generic_keywords as $word) {
        if (stripos(strtolower($keywords), $word) !== false) {
            $is_generic_query = true;
            break;
        }
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 3,
        'post_status'    => 'publish', // Garante que só produtos publicados sejam buscados.
    );

    if (!$is_generic_query) {
        $args['s'] = $keywords;
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return "Nenhum produto encontrado com base na sua pergunta.";
    }

    if ($is_generic_query) {
        $context = "Estes são alguns exemplos dos nossos produtos mais recentes:\n";
    } else {
        $context = "Produtos encontrados no sistema relacionados à sua busca:\n";
    }

    while ($query->have_posts()) {
        $query->the_post();
        $product = wc_get_product(get_the_ID());
        $context .= "- Nome: " . $product->get_name() . "\n";
        $context .= "  - Resumo: " . wp_strip_all_tags($product->get_short_description()) . "\n";
    }
    wp_reset_postdata();

    return $context;
}

/**
 * Chama a API do Google Gemini.
 */
function call_gemini_api($prompt) {
    // ATENÇÃO: COLOQUE SUA CHAVE DA API AQUI
    $api_key = '[CENSORED_GEMINI_API_KEY]';
    $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $api_key;

    $body = json_encode([
        'contents' => [
            ['parts' => [
                ['text' => $prompt]
            ]]
        ]
    ]);

    $response = wp_remote_post($api_url, [
        'method'  => 'POST',
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => $body,
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        return ['success' => false, 'reply' => null, 'error' => $response->get_error_message()];
    }

    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body, true);

    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        return ['success' => true, 'reply' => $data['candidates'][0]['content']['parts'][0]['text'], 'error' => null];
    } else {
        error_log('Erro na API Gemini: ' . $response_body);
        return ['success' => false, 'reply' => null, 'error' => 'Não foi possível extrair a resposta da API. Verifique os logs do servidor.'];
    }
    
}
/**
 * Altera a URL de redirecionamento quando o download do WooCommerce falha.
 * Em vez de ir para a página de erro, volta para a página anterior
 * com um parâmetro na URL para ativar o pop-up.
 */
add_action( 'woocommerce_download_product_access_denied', 'braga_force_download_limit_redirect', 10, 2 );

function braga_force_download_limit_redirect( $download_data ) {
    // Pega a URL da página de onde o usuário veio.
    $referer_url = wp_get_referer();

    // Se não conseguirmos a URL anterior, manda para a home como segurança.
    if ( ! $referer_url ) {
        $referer_url = home_url();
    }

    // Adiciona nosso "código secreto" na URL.
    $redirect_url_with_param = add_query_arg( 'download_error', 'limit_exceeded', $referer_url );

    // A "Marreta": Força o redirecionamento e para a execução de qualquer outra coisa.
    wp_redirect( $redirect_url_with_param );
    exit(); // Essencial para garantir que nada mais seja executado.
}