import { onUnmounted, ref } from 'vue';
import { useSiteApi } from './useInjections.js';

const SUCCESS_RESET_MS = 4000;

const defaultFormState = () => ({
    name: '',
    phone: '',
    email: '',
    message: '',
    channel: 'email',
});

export function useOrderForm() {
    const api = useSiteApi();

    const form = ref(defaultFormState());
    const submitting = ref(false);
    const successMessage = ref('');
    const errorMessage = ref('');
    const isSuccess = ref(false);

    let successResetTimer = null;

    const clearSuccessState = () => {
        successMessage.value = '';
        isSuccess.value = false;
        successResetTimer = null;
    };

    const resetForm = () => {
        form.value = defaultFormState();
    };

    const submit = async () => {
        submitting.value = true;
        clearSuccessState();
        errorMessage.value = '';

        if (successResetTimer) {
            clearTimeout(successResetTimer);
            successResetTimer = null;
        }

        try {
            const response = await api.orders.submit({ ...form.value });
            successMessage.value = response.message || 'Данные успешно отправлены!';
            isSuccess.value = true;
            resetForm();
            successResetTimer = setTimeout(clearSuccessState, SUCCESS_RESET_MS);
        } catch (cause) {
            const validationMessage = cause.response?.data?.errors
                ? Object.values(cause.response.data.errors).flat().join(' ')
                : null;

            errorMessage.value = validationMessage || 'Не удалось отправить заявку. Попробуйте позже.';
            console.error(cause);
        } finally {
            submitting.value = false;
        }
    };

    onUnmounted(() => {
        if (successResetTimer) {
            clearTimeout(successResetTimer);
        }
    });

    return {
        form,
        submitting,
        successMessage,
        errorMessage,
        isSuccess,
        submit,
    };
}
