<template>
  <header>
    <div class="w-100 bg-dark d-flex align-items-center justify-content-between" style="height: 50px;">
      <h1 class="mb-0 ms-4" style="color: aliceblue;">V</h1>
      <div class="links d-flex align-items-center">
        <RouterLink v-if="userProfile.user === null" class="text-decoration-none me-4" to="/register"><p class="mb-0">Sign up</p></RouterLink>
        <RouterLink class="text-decoration-none me-4" to="/login"><p class="mb-0">Sign in</p></RouterLink>
        <RouterLink v-if="userProfile.user" class="text-decoration-none me-4" to="/profile"><p class="mb-0">Profile</p></RouterLink>
        <RouterLink class="text-decoration-none me-4" to="/positions"><p class="mb-0">Positions</p></RouterLink>
        <RouterLink v-if="userProfile.user?.role === 'recruiter'" class="text-decoration-none me-4" to="/dashboard"><p class="mb-0">Dashboard</p></RouterLink>
        <a type="button" @click="clickOnHelp" v-if="userProfile.user" class="text-decoration-none me-4">Help</a>
      </div>
      
    </div>
  </header>



  <div 
  v-if="modalHelp.isOpen" 
  class="modal fade show d-block" 
  tabindex="-1" 
  style="background-color: rgba(0,0,0,0.5); z-index: 9999;" 
  @click.self="isOpen = false"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      
      <div class="modal-header">
        <h5 class="modal-title">Help</h5>
        <button type="button" class="btn-close" @click="modalHelp.isOpen = false; errorMessage = ''; successfuly = false"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label for="summary" class="form-label">Summary</label>
          <textarea 
            id="summary" 
            v-model="summary" 
            class="form-control" 
            rows="4" 
            placeholder="Enter summary..."
          ></textarea>
        </div>


        <div class="mb-3">
          <label for="priority" class="form-label">Priority</label>
          <select id="priority" v-model="priority" class="form-select">
            <option value="High">High</option>
            <option value="Average">Average</option>
            <option value="Low">Low</option>
          </select>
        </div>
      </div>

      <div v-if="errorMessage" class="d-flex justify-content-center">
        <span class="text-danger">{{ errorMessage }}</span>
      </div>

      <div v-if="successfuly === true" class="d-flex justify-content-center">
        <span class="text-success">Support ticket created successfully</span>
      </div>
      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="isClickOnHelp = false">Закрыть</button>
        <button v-if="isLoading === false" @click="sendSupportTicket" type="button" class="btn btn-primary" >Сохранить</button>
        <button v-else type="button" class="btn btn-primary" disabled><span v-if="isLoader" class="spinner-border" style="width: 20px; height: 20px;"></span></button>
      </div>



    </div>
  </div>
</div>
  <main class="min-vh-100">



   
      <RouterView />
    
  </main>

  <footer class="w-100 bg-dark" style="height: 150px; margin-top: 200px;"></footer>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useUserProfileStore } from '@/stores/UserProfileStore.js';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';
import { usePositionStore } from './stores/PositionStore';
import { useModalHelpStore } from './stores/ModalHelp';
import axios from './services/api.js';

const route = useRoute();
const router = useRouter();
console.log(route);
const userProfile = useUserProfileStore();
const isClickOnHelp = ref(false);
const summary = ref('');
const priority = ref('Low');
const errorMessage = ref('');
const positionStore = usePositionStore();
const modalHelp = useModalHelpStore();
const successfuly = ref(false);
const isLoading = ref(false);

onMounted(() => {

});

function clickOnHelp(){
  modalHelp.isOpen = true;
}

async function sendSupportTicket(){
  console.log("Отправка...");
  if (summary.value === ''){
    errorMessage.value = "Please enter a description of your problem";
  }
  let response = {};
  
  try{
    const name = `${userProfile.user.me.firstName} ${userProfile.user.me.lastName}`
    const adminEmails = import.meta.env.VITE_ADMIN_EMAILS ? import.meta.env.VITE_ADMIN_EMAILS.split(',') : ['default-admin@itransition.com'];
    response = {
      'reportedBy': `${name} (Role: ${userProfile.user.role})`,
      "position": positionStore.currentPosition ? positionStore.currentPosition.name : null,
      "priority": priority.value,
      "summary": summary.value,
      "link": positionStore.currentPosition ? `${window.location.origin}?id=${positionStore.currentPosition.id}` : window.location.origin,
      "adminEmails": adminEmails
    }
    isLoading.value = true;
    await axios.post(`/api/support/ticket`, response);
    successfuly.value = true;
    
  } catch (error){
    console.log(error);
    if (error.response){
      if (error.response.status === 401){
        errorMessage.value = "This user is not authorized. Please log in.";
        modalHelp.isOpen = false;
        router.push('/login');
      } else if (error.response.status === 500){
        errorMessage.value = "Server error. Please try again later.";
      } else {
        console.log(error.response.data);
        errorMessage.value = "An unexpected error occurred. Please try again later.";
      }
    }
  } finally {
    isLoading.value = false;
  }
}

</script>