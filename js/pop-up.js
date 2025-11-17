document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('download_error') === 'limit_exceeded') {
        
        // <<< LINHA DE DEBUG >>>
        console.log('Código secreto encontrado na URL! Tentando abrir o pop-up...');
        
        setTimeout(() => {
            if (window.elementorProFrontend && window.elementorProFrontend.modules.popup) {
                
                // TROQUE 1234 PELO ID DO SEU POP-UP
                const popupId = 1349; 
                console.log('Chamando pop-up do Elementor com ID:', popupId);
                elementorProFrontend.modules.popup.showPopup({ id: popupId });

            } else {
                console.error('ERRO: O script de pop-up do Elementor Pro não foi encontrado.');
            }
        }, 500);

    }
});