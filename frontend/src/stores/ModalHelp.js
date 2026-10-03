import { defineStore } from "pinia";
import { ref } from "vue";
import api from '../services/api.js';

export const useModalHelpStore = defineStore('help', () => {
   const isOpen = ref(false);

    return {
        isOpen
    };
})