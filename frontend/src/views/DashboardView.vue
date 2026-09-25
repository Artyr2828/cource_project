<template>
    <div class="container">
        <div>
            <h1>Dashboard</h1>
        </div>
    </div>
</template>

<script setup>
import { useUserProfileStore } from '@/stores/UserProfileStore.js';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
const userProfile = useUserProfileStore();
const router = useRouter();
onMounted(async () => {
    try {
        await userProfile.fetchProfile();
    } catch (error) {
        if (error.response?.status === 401) {
            router.push({
                path: '/login',
                query: {
                    error: 'Access to the profile page is prohibited for unauthorized users'
                }
            });
        }
    } finally {
        userProfile.isLoading = false;
    }
})
</script>