<template>
    <div v-if="userProfile.user?.role === 'recruiter' && userProfile.user !== null">
        <h1>Я рекрутер</h1>
    </div>
    
    <div v-if="userProfile.user?.role === 'candidate' && userProfile.user !== null">
        <h1>Я кандидат</h1>
    </div>

    <div v-if="userProfile.user === null">
        <h1>Я пользователь</h1>
    </div>
</template>


<script setup>
import {useUserProfileStore} from '@/stores/UserProfileStore.js'
import { useRouter } from 'vue-router';

const router = useRouter();
const userProfile = useUserProfileStore();


</script>