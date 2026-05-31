<script setup>
import { useOrderForm } from '../../composables/useOrderForm.js';

const {
    form,
    submitting,
    successMessage,
    errorMessage,
    isSuccess,
    submit,
} = useOrderForm();
</script>

<template>
    <form
        class="landing-form"
        :class="{ 'landing-form--success': isSuccess }"
        @submit.prevent="submit"
    >
        <div class="landing-form__row landing-form__row--2">
            <div>
                <label for="name">Ваше имя</label>
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    name="name"
                    required
                    placeholder="Иван"
                    :disabled="submitting"
                >
            </div>
            <div>
                <label for="phone">Телефон</label>
                <input
                    v-model="form.phone"
                    type="tel"
                    id="phone"
                    name="phone"
                    required
                    placeholder="+375 (29) 000-00-00"
                    :disabled="submitting"
                >
            </div>
        </div>
        <div>
            <label for="email">E-mail</label>
            <input
                v-model="form.email"
                type="email"
                id="email"
                name="email"
                required
                placeholder="mail@example.com"
                :disabled="submitting"
            >
        </div>
        <div>
            <label for="message">Описание проекта</label>
            <textarea
                v-model="form.message"
                id="message"
                name="message"
                placeholder="Расскажите о задаче…"
                :disabled="submitting"
            ></textarea>
        </div>
        <button
            type="submit"
            class="landing-btn landing-btn--primary"
            :class="{ 'landing-btn--success': isSuccess }"
            :disabled="submitting || isSuccess"
        >
            {{
                submitting
                    ? 'Отправка…'
                    : isSuccess
                        ? 'Данные отправлены!'
                        : 'Отправить заявку'
            }}
        </button>
        <p v-if="successMessage" class="landing-form__feedback landing-form__feedback--success" role="status">
            {{ successMessage }}
        </p>
        <p v-if="errorMessage" class="landing-form__feedback landing-form__feedback--error">
            {{ errorMessage }}
        </p>
        <p class="landing-form__note">
            * Я делаю проекты, которые работают на ваш бизнес: продают, приносят прибыль и укрепляют имидж.
        </p>
    </form>
</template>

<style scoped>
.landing-form {
    transition: background-color 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
}

.landing-form--success {
    border-color: #067647;
    background-color: rgba(6, 118, 71, 0.08);
    box-shadow: 0 0 0 1px rgba(6, 118, 71, 0.15);
}

.landing-btn--success {
    background-color: #067647 !important;
    border-color: #067647 !important;
    color: #fff !important;
}

.landing-form__feedback {
    margin: 12px 0 0;
    font-size: 0.9rem;
}

.landing-form__feedback--success {
    color: #067647;
}

.landing-form__feedback--error {
    color: #b42318;
}
</style>
