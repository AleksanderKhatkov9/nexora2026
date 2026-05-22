<section id="contact" class="landing-section landing-section--alt">
    <div class="landing-container landing-contact">
        <div>
            <h2 class="landing-section__title">Доверьте нам ваш проект</h2>
            <p class="landing-section__subtitle" style="margin-bottom: 0;">
                Оставьте заявку — свяжемся в удобное время и обсудим задачи, сроки и бюджет.
            </p>
        </div>

        <form class="landing-form" action="#" method="post">
            @csrf
            <div class="landing-form__row landing-form__row--2">
                <div>
                    <label for="name">Ваше имя</label>
                    <input type="text" id="name" name="name" required placeholder="Иван">
                </div>
                <div>
                    <label for="phone">Телефон</label>
                    <input type="tel" id="phone" name="phone" required placeholder="+375 (29) 000-00-00">
                </div>
            </div>
            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="mail@example.com">
            </div>
            <div>
                <label for="message">Описание проекта</label>
                <textarea id="message" name="message" placeholder="Расскажите о задаче…"></textarea>
            </div>
            <div>
                <span style="display:block;margin-bottom:10px;font-size:0.85rem;font-weight:500;color:var(--nx-text-muted);">Как удобнее связаться?</span>
                <div class="landing-form__channels">
                    <label><input type="radio" name="channel" value="email" checked> E-mail</label>
                    <label><input type="radio" name="channel" value="phone"> Телефон</label>
                    <label><input type="radio" name="channel" value="telegram"> Telegram</label>
                    <label><input type="radio" name="channel" value="viber"> Viber</label>
                </div>
            </div>
            <button type="submit" class="landing-btn landing-btn--primary">Отправить заявку</button>
            <p class="landing-form__note">* Мы делаем проекты, которые работают на ваш бизнес: продают, приносят прибыль и укрепляют имидж.</p>
        </form>
    </div>
</section>
