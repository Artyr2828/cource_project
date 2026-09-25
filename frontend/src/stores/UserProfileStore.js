import { defineStore } from "pinia";
import { ref } from "vue";
import api from '../services/api.js';
import { useRouter } from "vue-router";
import { useRoute } from "vue-router";


export const useUserProfileStore = defineStore('user', () => {
    const user = ref(null);
    const errorMessage = ref('');
    const isLoading = ref(true);
    const router = useRouter();
    const route = useRoute();
    async function fetchProfile(){
        if (user.value){
            return;
        }
        
        
        isLoading.value = true;
        errorMessage.value = '';
      
            
            const response = await api.get('/api/profile/me');
            user.value = response.data;
            
    }
        
    

    return {
        user,
        errorMessage,
        fetchProfile,
        isLoading
    };
})