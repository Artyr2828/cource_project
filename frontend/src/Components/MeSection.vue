<template>
            <uc-config
                ctx-name="my-avatar-uploader"
                pubkey="6a5a21105b2bdff19cda"
                img-only="true"
                multiple="false"
                use-cloud-image-editor="true"
            ></uc-config>

            <uc-upload-ctx-provider
                id="avatar-uploader-ctx"
                ctx-name="my-avatar-uploader"
               
            ></uc-upload-ctx-provider>
    <div class="header w-100 d-flex justify-content-between bg-secondary rounded" style="margin-top: 50px;">
                    <h1 class="d-inline ms-2" style="color: honeydew;">Me</h1>
                    <button @click="openOrClosingEditing" class="btn  btn-sm bi-pencil d-inline-flex align-self-center p-2 me-2 btn-warning"></button>
            </div>

            <div v-if="isEditing" class="bg-light rounded ps-2 pt-3">
                
                <div class="d-flex flex-column">
                    
                    <div class="d-flex align-items-center" style="height: 70px;">
                        
                        
                        <div class="avatar-area position-relative ms-2" style="height: 100%;">
                                <div class="avatar">
                                    <img v-if="isNotLoad" :src="userProfile.user?.me?.avatarUrl" alt="" class="rounded  object-fit-cover" style="height: 70px; width: 70px;" >
                                    <img v-else src="https://media1.tenor.com/m/P3jIMIC96psAAAAC/ggg.gif" alt="" class="rounded  object-fit-cover" style="height: 70px; width: 70px;" >
                                </div>
                            
                        <div v-if="onAvatar" class="position-absolute bg-secondary top-0 start-0 w-100 h-100 rounded text-center" style="pointer-events: none;">Drop here</div> 
                        </div>

                        <div v-if="userProfile.user?.me?.firstName || userProfile.user?.me?.lastName" class="Name">
                             <p class="ms-2 h4">{{ userProfile.user?.me?.firstName }} {{ userProfile.user?.me?.lastName }}</p>
                        </div>

                        <div v-else class="Name">
                            <p class="ms-2">Without a first and last name</p>
                        </div>
        
                    </div>

                    <div class="p-3 rounded mt-2" style="background-color: #E4EAED;">
                        <div class="row g-2">
    
                        <div class="col-6">
                            <label class="form-label text-muted small fw-medium mb-1">First Name</label>
                            <p v-if="userProfile.user?.me?.firstName" class="fw-semibold h5 mb-0">{{ userProfile.user.me.firstName }}</p>
                            <p v-else class="text-muted h5 fst-italic mb-0">not specified</p>
                        </div>

                        <div class="col-6">
                            <label class="form-label text-muted small fw-medium mb-1">Last Name</label>
                            <p v-if="userProfile.user?.me?.lastName" class="fw-semibold h5 mb-0">{{ userProfile.user.me.lastName }}</p>
                            <p v-else class="text-muted h5 fst-italic mb-0">not specified</p>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="form-label text-muted small fw-medium mb-1">Location</label>
                            <p v-if="userProfile.user?.me?.location" class="fw-semibold h5 mb-0">
                                {{ userProfile.user.me.location }}
                            </p>
                            <p v-else class="text-muted h5 fst-italic mb-0">
                                not specified
                            </p>
                        </div>
                        </div>
                </div>



                
                </div>
            </div>











            <div v-else="isEditing" class="bg-light rounded ps-2 pt-3">
            
                <div class="d-flex flex-column">
                    
                    <div class="d-flex align-items-center" style="height: 70px;">
                       
                        
                        <div @click="initFlow" class="avatar-area position-relative ms-2" style="height: 100%;">
                            
                            
                                <div class="avatar">
                                    <img v-if="isNotLoad" :src="userProfile.user?.me?.avatarUrl" alt="" class="rounded  object-fit-cover" style="height: 70px; width: 70px;" >
                                    <img v-else src="https://media1.tenor.com/m/P3jIMIC96psAAAAC/ggg.gif" alt="" class="rounded  object-fit-cover" style="height: 70px; width: 70px;" >
                                </div>
                            
                            
                            <div class="overAvatar position-absolute w-100 h-100 bg-dark top-0 start-0 bg-opacity-50 rounded d-flex align-items-center justify-content-center"><p class="text-body-tertiary" style="font-size: 17px;">Change</p></div>

                            <uc-file-uploader-regular
                                class="area position-absolute top-0 start-0 h-100 w-100"
                                @dragenter.prevent="onDragEnter"
                                @dragover.prevent
                                @drop.prevent="initFlow"
                                @dragleave="onDragLeave"
                                @mouseenter="onMouseEnter"
                                @mouseleave="onMouseLeave"
                                ctx-name="my-avatar-uploader"
                                headless
                            >
                            </uc-file-uploader-regular>
                        <div v-if="onAvatar" class="position-absolute bg-secondary top-0 start-0 w-100 h-100 rounded text-center" style="pointer-events: none;">Drop here</div> 
                        </div>

                        <div v-if="userProfile.user?.me?.firstName || userProfile.user?.me?.lastName" class="Name">
                             <p class="ms-2 h4">{{ userProfile.user?.me?.firstName }} {{ userProfile.user?.me?.lastName }}</p>
                        </div>

                        <div v-else class="Name">
                            <p class="ms-2">Without a first and last name</p>
                        </div>
                        
                        
                        
                    </div>
                     

                    <div v-if="userProfile.user" class="row g-2 mt-2">
                        
                            <div class="col-6">
                                <label class="form-label text-muted small fw-medium mb-1">First Name</label>
                                <input v-model.trim="userProfile.user.me.firstName" :class="{'is-invalid': getMeError('firstName')}" :title="getMeError('firstName')" class="form-control form-control-sm" placeholder="Your first name"> 
                                <small v-if="getMeError('firstName')" class="small text-danger">{{ getMeError('firstName') }}</small>
                            </div>
                            
                            <div class="col-6">
                                <label class="form-label text-muted small fw-medium mb-1">Last Name</label>
                                <input v-model="userProfile.user.me.lastName" :class="{'is-invalid': getMeError('lastName')}" :title="getMeError('lastName')" class="form-control form-control-sm" placeholder="Your last name"></input>
                                <small v-if="getMeError('lastName')" class="small text-danger">{{ getMeError('lastName') }}</small> 
                            </div>

                            <div class="col-12 mt-2">
                                     <label class="form-label text-muted small fw-medium mb-1">Location</label>
                                    <input v-model="userProfile.user.me.location" :class="{'is-invalid': getMeError('location')}" :title="getMeError('location')" class="form-control form-control-sm" placeholder="Your location">
                                    <small v-if="getMeError('location')" class="small text-danger">{{ getMeError('location') }}</small>
                            </div>
                    </div>


                </div>
            </div>



            <Transition name="toast-fade">
                <div v-if="errorMessage" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
                    <div class="toast show align-items-center text-bg-danger border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">{{ errorMessage }}</div>
        
                        <button 
                            type="button" 
                            class="btn-close btn-close-white me-2 m-auto" 
                            @click="errorMessage = ''"
                        ></button>
                    </div>
                    </div>
                </div>
            </Transition>

            <Transition name="toast-fade">
                <div v-if="sucessfully" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
                    <div class="toast show align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">The changes have been saved</div>
        
                        <button 
                            type="button" 
                            class="btn-close btn-close-white me-2 m-auto" 
                            @click="sucessfully = false"
                        ></button>
                    </div>
                    </div>
                </div>
            </Transition>
</template>

<script setup>
import { useUserProfileStore } from '@/stores/UserProfileStore.js';
import * as UC from '@uploadcare/file-uploader';
import * as UCEditor from '@uploadcare/file-uploader/web/uc-cloud-image-editor.min.js';
import { computed, onMounted, onUnmounted, ref, Transition, watch } from 'vue';
UC.defineComponents(UC);
UCEditor.defineComponents(UCEditor);
const userProfile = useUserProfileStore();
const onAvatar = ref(false);
let api;

const errors = ref([]);

onMounted(async () => {
    if (!userProfile.user?.me?.avatarUrl){
       userProfile.user.me.avatarUrl = "https://static.vecteezy.com/system/resources/previews/069/428/996/large_2x/default-profile-picture-social-media-icon-user-avatar-isolated-symbol-on-white-background-illustration-vector.jpg";
    }
     
    if (userProfile.errorMessage.value){
        console.log(userProfile.errorMessage.value);
        return;
    }
    const ctxAvatar = document.querySelector('#avatar-uploader-ctx');
     console.log('ctxAvatar:', ctxAvatar);
    api = ctxAvatar.getAPI();


    api.on('file-upload-success', (file) => {
        userProfile.user.me.avatarUrl = file.cdnUrl;
        isNotLoad.value = true;
        
 
    });

    api.on('file-added', (event) => {
       isNotLoad.value = false;
    });

    api.on('change', (event) => {
        const state = api.getOutputCollectionState();
        const file = state.allEntries[0];
        if (file.cdnUrl){
            userProfile.user.me.avatarUrl = file.cdnUrl;
        }
    });

    

    
});

function getMeError(field){
        return errors.value.find(
            error => error.field === field
        )?.message
    }

</script>