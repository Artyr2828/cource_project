
<template>
<div class="container justify-content-center vh-100 d-flex align-items-center">
  <div class="card p-4" style="width: 100%; max-width: 400px;">
    <h1 class="text-center">Register</h1>
     <input v-model="email" class="form-control mt-3" placeholder="Email">
     <input v-model="password" class="form-control mt-2" placeholder="Password">
     
     <button :disabled="isLoader" @click="register" class="btn btn-primary mt-3">
      <span v-if="isLoader" class="spinner-border text-center" style="width: 20px; height: 20px;"></span>
      <span v-else class="text-center fw-bold">Register</span>
      
    </button>
     <span  v-if="errorMessage" :key="errorKey" class="text-danger text-center flash-once" style="padding-top: 5px;">{{ errorMessage }}</span>
     <span v-else class="form-text text-center" style="padding-top: 5px;">Make sure the password consists of 8 characters</span>
     <p class="text-center mb-0 mt-3 fst-italic">Already have an account? <RouterLink to="/login" style="text-decoration: none;">Login</RouterLink></p>
    
  </div>
   
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import api from '../services/api.js';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoader = ref(false);
const errorKey = ref(0);
const router = useRouter();

async function register(event) {
 
  
  errorKey.value++;
  
  if (!email.value || !password.value) {
    errorMessage.value = 'Email and password are required';
    
    return;
  }
  if (password.value.length < 8) {
    errorMessage.value = 'Password must be at least 8 characters long';
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errorMessage.value = 'Email is Incorrect';
    return;
  }
   isLoader.value = true;
  
  event.preventDefault();
  try{
    errorMessage.value = '';
    const response = await api.post('/api/register', {
      email: email.value,
      password: password.value
    });
    localStorage.setItem('token', response.data.token);
    router.push('/');
  } catch (error) {
    if (error.request) {
      if (error.response === undefined) {
          errorMessage.value = 'Unable to connect to the server. Check your internet connection or try again later.';
      } else {
        errorMessage.value = error.response.data.message || 'An error occurred during registration';
      }
      return;
    }
  } finally {
    isLoader.value = false;

  }
}
</script>

<style scoped>
    @keyframes flash {
        0% {opacity: 0;}
        50% {opacity: 0.3;}
        100% {opacity: 1;}
    }

    .flash-once {
        animation: flash 0.3s ease-in-out 1;
    }
</style>
