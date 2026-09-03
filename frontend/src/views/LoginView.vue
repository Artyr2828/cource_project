<template>
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center">Login</h1>
            <input v-model="email" class="form-control mt-3" placeholder="Email">
            <input v-model="password" class="form-control mt-2" placeholder="Password">
            
            <button @click="login" class="btn btn-primary mt-3">
                <span v-if="isLoader" class="spinner-border" style="width: 20px; height: 20px;"></span>
                <span v-else class="text-center fw-bold">Login</span>
            </button>
            <span :key="errorCount" v-if="errorMessage" class="text-danger text-center mt-2 flash">{{ errorMessage }}</span>
            <span class="fst-italic text-center mt-2">Don't you have an account yet? <RouterLink to="/register" style="text-decoration: none;">Register</RouterLink></span>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';  
import axios from 'axios';
import api from '../services/api.js';
import { useRouter } from 'vue-router';
const router = useRouter();
const email = ref('cs');
const password = ref('');
const errorMessage = ref('');
const isLoader = ref(false);
const errorCount = ref(0);

async function login() {
    errorCount.value++;
    if (!email.value || !password.value){
        errorMessage.value = 'Email and password are required';
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)){
        errorMessage.value = 'Email is Incorrect';
        return;
    }
    if (password.value.length < 8) {
        errorMessage.value = 'Password must be at least 8 characters long';
        return;
    }
    isLoader.value = true;
    errorMessage.value = '';
    try {
        const response = await api.post('/api/login', {
            email: email.value,
            password: password.value
        });
        router.push('/profile');
    } catch (error) {
        
        if (error.request) {
            if (error.response === undefined) {
                errorMessage.value = 'Unable to connect to the server. Check your internet connection or try again later.';
            } else {
                errorMessage.value = error.response.data?.message || 'Login failed. Please check your credentials.';
            }
        } else {
            errorMessage.value = 'An unexpected error occurred. Please try again later.';
        }
    }
    finally {
        isLoader.value = false;
    }
}

</script>

<style scoped>
@keyframes flashError {
    0% {opacity: 0;}
    50% {opacity: 0.3;}
    100% {opacity: 1;}
}
.flash {
    animation: flashError 0.3s ease-in-out 1;
}
</style>