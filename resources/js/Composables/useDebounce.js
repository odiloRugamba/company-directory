import { ref } from 'vue';

export function useDebounce(fn, delay = 400) {
    let timer = null;

    const debounced = (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };

    const cancel = () => clearTimeout(timer);

    return { debounced, cancel };
}
