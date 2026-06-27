<div class="nk-chatbot" data-nk-chatbot data-endpoint="<?= ROOT ?>/Assistants/chat" data-bot-name="N'KadonBot">
    <section class="nk-chatbot__panel" data-nk-chat-panel aria-label="Assistant IA NGAKODON">
        <header class="nk-chatbot__header">
            <div class="nk-chatbot__identity">
                <span class="nk-chatbot__title">N'KadonBot</span>
                <span class="nk-chatbot__subtitle">Assistant de la plateforme</span>
            </div>
            <div class="nk-chatbot__actions">
                <button class="nk-chatbot__icon-btn" type="button" data-nk-chat-clear title="Effacer la conversation" aria-label="Effacer la conversation">
                    <i class="bx bx-trash"></i>
                </button>
                <button class="nk-chatbot__icon-btn" type="button" data-nk-chat-close title="Reduire" aria-label="Reduire">
                    <i class="bx bx-minus"></i>
                </button>
            </div>
        </header>
        <div class="nk-chatbot__messages" data-nk-chat-messages>
            <div class="nk-chatbot__typing" data-nk-chat-typing aria-live="polite" aria-label="N'KadonBot ecrit">
                <span class="nk-chatbot__dot"></span>
                <span class="nk-chatbot__dot"></span>
                <span class="nk-chatbot__dot"></span>
            </div>
        </div>
        <form class="nk-chatbot__form" data-nk-chat-form>
            <textarea class="nk-chatbot__input" data-nk-chat-input rows="1" placeholder="Posez votre question..."></textarea>
            <button class="nk-chatbot__send" data-nk-chat-send type="submit" title="Envoyer" aria-label="Envoyer">
                <i class="bx bx-send"></i>
            </button>
        </form>
    </section>
    <button class="nk-chatbot__toggle" type="button" data-nk-chat-toggle aria-expanded="false" title="Ouvrir N'KadonBot" aria-label="Ouvrir N'KadonBot">
        <i class="bx bx-message-rounded-dots"></i>
    </button>
</div>
