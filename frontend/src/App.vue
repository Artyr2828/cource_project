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
       
      </div>

      
    </div>
  </header>

  <main class="min-vh-100">
   
      <RouterView />
    
  </main>

  <footer class="w-100 bg-dark" style="height: 150px; margin-top: 200px;"></footer>
</template>

<script setup>
import { onMounted } from 'vue';
import { useUserProfileStore } from '@/stores/UserProfileStore.js';
import { useRoute } from 'vue-router';


 
const userProfile = useUserProfileStore();
console.log(userProfile.user);
onMounted(() => {

});



</script>