import { defineStore } from "pinia";
import { ref } from "vue";
import api from '../services/api.js';


export const usePositionStore = defineStore('position', () => {
    const position = ref(null);
    const positionPrevios = ref(null);
    const positionNext = ref(null);
    const errorMessage = ref('');
    const isLoading = ref(true);

    async function fetchPosition(idPosition = null){
        isLoading.value = true;
        errorMessage.value = '';

        
        try{            
            let parametres = {};
            if (position.value?.positions?.length === 0 || idPosition === null || position.value === null){
                parametres = {
                    params: {}
                }
              
            } else if (position.value.positions[position.value.positions.length - 1].id === idPosition){
                parametres = {
                    params: {
                        after: idPosition
                    }
                }
            } else if (position.value.positions[0].id === idPosition){
                 parametres = {
                    params: {
                        before: idPosition
                    }
                }
            } else {
                 parametres = {params: {}}
                console.log("Invalid IdPosition");
            }

            if (position.value !== null){
                if (parametres.params.after){
                    if (position.value.hasNext === false){
                        console.log("Доступа к следущим данным нету");
                        return;
                    }
                } else if (parametres.params.before){
                    if (position.value.hasPrevios === false){
                        console.log("Доступа к предыдущим данным нету");
                        return;
                    }
                }
                
            }
            const response = await api.get('/api/position', parametres);
            
            console.log("data: ", response.data);
            position.value = response.data;
            
            console.log("Position data successfully");
            
        } catch (error) {
           
            
            console.log("Doxodit: ", error);
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
        position,
        errorMessage,
        fetchPosition,
        isLoading,
        positionPrevios
    };
})