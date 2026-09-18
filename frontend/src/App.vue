<template>
  <header>
    <div class="w-100 bg-dark d-flex align-items-center justify-content-between" style="height: 50px;">
      <h1 class="mb-0 ms-4" style="color: aliceblue;">V</h1>
      <div class="links d-flex align-items-center">
        <RouterLink class="text-decoration-none me-4" to="/login"><p class="mb-0">Sign in</p></RouterLink>
        <RouterLink class="text-decoration-none me-4" to="/profile"><p class="mb-0">Profile</p></RouterLink>
       
      </div>

      
    </div>
  </header>

  <main>
   
      <RouterView />
    
  </main>

  <footer class="w-100 bg-dark" style="height: 150px; margin-top: 200px;"></footer>
</template>

<script setup>
import {useUserProfileStore} from '@/stores/UserProfileStore.js'
import { onMounted } from 'vue';
const useUserProfile = useUserProfileStore();

onMounted(() => {
  useUserProfile.fetchProfile();
});



</script>