import { defineStore } from "pinia";
import { ref } from "vue";
import api from '../services/api.js';

export const useUserProfileStore = defineStore('user', () => {
    const user = ref(null);
    const errorMessage = ref('');
    const isLoading = ref(false);

    async function fetchProfile(){
        if (user.value){
            return;
        }
        isLoading.value = true;
        errorMessage.value = '';
        try{
            const response = await api.get('/api/profile/me');
            user.value = response.data;
            
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
        user,
        errorMessage,
        fetchProfile,
        isLoading
    };
})