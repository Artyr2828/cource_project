
<template>
<div class="container justify-content-center vh-100 d-flex align-items-center ">
  <div class="card p-4" style="width: 100%; max-width: 400px;">
    <h1>Register</h1>
     <input v-model="email" class="form-control mt-3" placeholder="Email">
     <input v-model="password" class="form-control mt-2" placeholder="Password">
     <span v-if="isLoader" @click="register" class="spinner-grow mt-3"></span>
     <button v-else @click="register" class="btn btn-primary mt-3">Register</button>
     <span v-if="errorMessage" class="text-danger mt-2">{{ errorMessage }}</span>
  </div>
</div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
const email = ref('c');
const password = ref('csx');
const errorMessage = ref('');
const isLoader = ref(false);

async function register(event) {
  isLoader.value = true;
  event.preventDefault();
  try{
    const response = await axios.post('/register', {
      email: email.value,
      password: password.value
    });
  } catch (error) {
    errorMessage.value = error.response.data?.message || 'Invalid email or password';
  } finally {
    isLoader.value = false;
  }
}
</script>

<style scoped></style>
