import { defineStore } from "pinia";
import { ref } from "vue";
import api from '../services/api.js';

export const useAttributeStore = defineStore('attribute', () => {
    const attributes = ref(null);
    const errorMessage = ref('');
    const isLoading = ref(false);


    async function fetchAttribute(){
        if (attributes.value){
            return;
        }
        isLoading.value = true;
        errorMessage.value = '';
        try{
            const response = await api.get('/api/attribute');
            attributes.value = response.data;
            
        } catch (error) {
            
            
            if (!error.response){
                 errorMessage.value = "Server issues—please try again later";
            }else{
                errorMessage.value = error.response.data?.message;
            }
        } finally {
            isLoading.value = false;
        }
    }

    return {
        attributes,
        errorMessage,
        fetchAttribute,
        isLoading
    };
})