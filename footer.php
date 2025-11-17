<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Esta parte carrega o rodapé visual do Elementor. A gente não mexe aqui.
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    if ( hello_elementor_display_header_footer() ) {
        if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
            get_template_part( 'template-parts/dynamic-footer' );
        } else {
            get_template_part( 'template-parts/footer' );
        }
    }
}
?>

<?php wp_footer(); // Hook sagrado do WordPress. Scripts são carregados aqui. ?>


<?php // <<< SEU CÓDIGO DO CHATBOT ENTRA EXATAMENTE AQUI >>> ?>
<div id="chat-launcher">
    <div class="notification-badge">1</div>
    <img src="https://websbraga.com/wp-content/uploads/2025/08/Atendente-BIA.png" alt="Abrir Chat">
</div>

<div id="meu-chat" class="chat-hidden">
    <div id="chat-header">
        <span>BIA</span>
        <button id="chat-close-btn">&times;</button>
    </div>
    <div id="chat-body">
        <div class="chat-message bot">
    <div class="avatar">
        <img src="https://websbraga.com/wp-content/uploads/2025/08/Atendente-BIA.png" alt="BIA">
    </div>
    <div class="message-content">
        <span>Oi! Sou a BIA, sua assistente aqui na Braga Designs Web 💻
Está procurando algo específico? Posso te mostrar nossos serviços ou tirar dúvidas — é só me dizer
</span>
    </div>
</div>
    </div>
    <div id="chat-footer">
        <form id="chat-form">
            <input type="text" id="chat-input" placeholder="Digite sua mensagem..." autocomplete="off">
            <button type="submit" aria-label="Enviar"></button> </form>
        </form>
    </div>
</div>
<?php // <<< FIM DO CÓDIGO DO CHATBOT >>> ?>


</body>
</html>