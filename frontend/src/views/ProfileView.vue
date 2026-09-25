<template v-if="userProfile.user">

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@uploadcare/file-uploader@v1/web/uc-file-uploader-minimal.min.css"
/>
    <div v-if="userProfile.user !== null && userProfile.isLoading !== true" class="container p-2 pt-3">
        <div class="container d-flex align-items-start gap-3 p-2 rounded" style="height: 100px; background-color: antiquewhite;" >
            <img v-if="isNotLoad" :src="userProfile.user?.me?.avatarUrl" alt="" class="rounded object-fit-cover" style="height: 100%; width: auto; object-fit: cover;">
            <img v-else="isNotLoad" src="https://media1.tenor.com/m/P3jIMIC96psAAAAC/ggg.gif" alt="" class="rounded" style="max-height: 100%; width: auto; object-fit: cover;">
            <div class="ps-2">
                <p v-if="userProfile.user?.me?.firstName || userProfile.user?.me?.lastName" class="d-inline-block mb-0 fs-3">{{ userProfile.user?.me?.firstName }} {{ userProfile.user?.me?.lastName }}</p>
                <p v-else class="d-inline-block mb-0 fs-3 text-body-secondary small fst-italic">Not specified</p>
                
                <p v-if="userProfile.user?.me?.location" class="fs-5">{{ userProfile.user.me.location }}</p>
                <p v-else class="fs-5">The location is not specified</p>
            </div>
        </div>

        <div class="container">
            <uc-config
                ctx-name="my-avatar-uploader"
                pubkey="6a5a21105b2bdff19cda"
                img-only="true"
                multiple="false"
                use-cloud-image-editor="true"
            ></uc-config>

           <uc-config
                ctx-name="my-attribute-uploader"
                pubkey="6a5a21105b2bdff19cda"
                img-only="true"
                multiple="false"
                use-cloud-image-editor="true"
            ></uc-config>

            <uc-upload-ctx-provider
                id="avatar-uploader-ctx"
                ctx-name="my-avatar-uploader"
               
            ></uc-upload-ctx-provider>
                        
            <uc-upload-ctx-provider
                id="attribute-uploader-ctx"
                ctx-name="my-attribute-uploader"
               
            ></uc-upload-ctx-provider>
            
            

            <!--Секция Me-->
            <div class="header w-100 d-flex justify-content-between bg-secondary rounded" style="margin-top: 50px;">
                    <h1 class="d-inline ms-2" style="color: honeydew;">Me</h1>
                    <button v-if="userProfile.user?.role === 'candidate'" @click="openOrClosingEditing" class="btn  btn-sm bi-pencil d-inline-flex align-self-center p-2 me-2 btn-warning"></button>
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











            <div v-else class="bg-light rounded ps-2 pt-3">
            
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

        <div v-if="userProfile.user?.role === 'candidate'">
             <!--Секция Info-->
            <div class="header w-100 d-flex justify-content-between align-items-center bg-secondary rounded" style="margin-top: 50px;">
                    <h1 class="d-inline ms-2" style="color: honeydew;">Info</h1>
                    <div>
                        <button v-if="isEditing === false" @click="deleteAttributes" class="btn btn-sm btn-danger me-1"> <i class="bi bi-trash"></i> </button>
                        <button @click="openModalAttributes" class="btn  btn-sm d-inline-flex align-self-center p-2 me-2 btn-warning">Add Attribute</button>
                    </div>
            </div>

            <!--Modal-->
            <div v-if="isModalAttributesOpen" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" @click.self="isModalAttributesOpen = false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Info</h5>
                            <button type="button" class="btn-close" @click="isModalAttributesOpen = false"></button>
                        </div>

                        <div class="modal-body">
                            


                            <div v-for="(items, category) in groupedAttributes" :key="category">
                                <h3>{{ category }}</h3>

                                <div
                                    v-for="attribute in items"
                                    :key="attribute.id"
                                    class="form-check"
                                >
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        :id="'attribute-' + attribute.id"
                                        :value="attribute"
                                         v-model="selectedAttributes"
                                    >

                                    <label
                                        class="form-check-label"
                                        :for="'attribute-' + attribute.id"
                                    >
                                        {{ attribute.name }}
                                    </label>
                                </div>
                            </div>

                            <button
                                class="btn btn-warning mt-3"
                                @click="addSelectedAttributes"
                            >
                                 Add
                            </button>


                            
                        </div>
                    </div>
                </div>
            </div>




        <div v-if="isEditing ">
             <div class="bg-light rounded ps-2 pt-3">
                <div class="d-flex flex-column bg-light">
                    <div v-for="userAttribute in userProfile?.user?.attributes" :key="userAttribute.id">
                        <div class="d-flex rounded justify-content-between align-items-center mt-3" style="background-color:antiquewhite;">
                            <label>{{ userAttribute.attribute?.name }}</label>
                            <p v-if="userAttribute.attribute?.type === 'string' && userAttribute?.value" class="me-2 mb-0"> {{ userAttribute?.value }} </p>
                            <textarea v-else-if="userAttribute.attribute?.type === 'text' && userAttribute?.value" class="me-2" disabled> {{ userAttribute?.value }} </textarea>
                            <p v-else-if="userAttribute.attribute?.type === 'numeric' && userAttribute?.value !== ''" class="me-2 mb-0"> {{ userAttribute?.value }} </p>
                            <p v-else-if="userAttribute.attribute?.type === 'date' && userAttribute?.value" class="me-2 mb-0"> {{ userAttribute?.value }} </p>
                            <p v-else-if="userAttribute.attribute?.type === 'boolean' && userAttribute?.value" class="me-2 mb-0"> true </p>
    
                            <p v-else-if="userAttribute.attribute?.type === 'boolean' && userAttribute?.value === false" disabled class="me-2 mb-0">{{ userAttribute?.value }}</p>
                             <p v-else-if="userAttribute.attribute?.type === 'boolean' && userAttribute?.value === ''" disabled class="me-2 mb-0">false</p>
                             
                             <div v-else-if="userAttribute.attribute.type === 'period'">
                                <p class="mb-0">from: {{ userAttribute.value?.from }}</p>
                                <p class="mb-2">to: {{ userAttribute.value?.to }}</p>
                             </div>



                                <div v-else-if="userAttribute.attribute.type === 'image'" class="position-relative rounded"> 
                                    <div style="width: 80px; height: 80px;">
                                        <div v-if="getAttributeUrl(userAttribute)" class="w-100 h-100">
                                            <img class="w-100 h-100 rounded" :src="userAttribute.value" alt="">
                                        </div>
                                        <div v-else class="w-100 h-100 bg-dark rounded d-flex align-items-center"><p class="text-center" style="color: white;">There is no image</p></div>
                                    </div>
                                </div>

                            <div v-else-if="userAttribute.attribute.type === 'one_of_many'">
                                <p v-if="userAttribute.value" class="me-2 mb-0">{{ userAttribute.value }}</p>
                                <p v-else class="me-2 mb-0">not selected</p>
                            </div>


                             <p v-if="userAttribute.value === '' && userAttribute.attribute.type !== 'image'" class="mb-0">Not Data</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div v-else>
            <div class="bg-light rounded ps-2 pt-3">
                <div class="d-flex flex-column bg-light">
                    <div v-for="userAttribute in userProfile.user.attributes" :key="id">
                        <div class="row rounded mt-2">

                            <div class="col-6 d-flex align-items-center">
                                <input type="checkbox" class="me-2" :value="userAttribute" v-model="selectedAttributes">
                                <label class="">{{ userAttribute.attribute.name }}</label>
                            </div>

                            <div class="col-6">
                                <input v-if="userAttribute.attribute.type === 'string'" type="text" v-model="userAttribute.value" class="form-control form-control-sm col-6 me-2">
                                <textarea v-else-if="userAttribute.attribute.type === 'text'"  v-model="userAttribute.value" class="me-1"></textarea>
                                <input v-else-if="userAttribute.attribute.type === 'string'" type="text" v-model="userAttribute.value" class="form-control form-control-sm col-6 me-2">
                                <input v-else-if="userAttribute.attribute.type === 'numeric' && getAttributeError(userAttribute.attribute.id) !== true" :class="{'is-invalid': getAttributeError(userAttribute.attribute.id)}" type="number" min="0" @input="userAttribute.value = Math.max(0, userAttribute.value)" v-model="userAttribute.value" class="form-control form-control-sm col-6 me-2">
                                <div v-else-if="userAttribute.attribute.type === 'numeric' && getAttributeError(userAttribute.attribute.id) === true">
                                    <input  type="number" min="0" @input="userAttribute.value = Math.max(0, userAttribute.value)" v-model="userAttribute.value" class="form-control form-control-sm col-6 me-2 is-invalid" title="">
                                        
                                </div> 
                                <input v-else-if="userAttribute.attribute.type === 'date'" type="date" v-model="userAttribute.value" class="form-control form-control-sm col-6 me-2">
                                <input v-else-if="userAttribute.attribute.type === 'boolean'" type="checkbox" v-model="userAttribute.value" class="form-check form-check-input col-6 me-2">
                                <div v-else-if="userAttribute.attribute.type === 'period'">
                                    <input  type="date" v-model="userAttribute.value.from">
                                    <input :min="userAttribute.value.from" type="date" v-model="userAttribute.value.to">
                                </div>

                                <div  @click="initAttributeFlow" v-else-if="userAttribute.attribute.type === 'image'" class="position-relative rounded"> 
                                    <div class="position-relative" style="width: 80px; height: 80px;">
                                        <div v-if="getAttributeUrl(userAttribute)" class="w-100 h-100">
                                            <img class="w-100 h-100 rounded" :src="userAttribute.value" alt="">
                                            <div class="position-absolute w-100 h-100 top-0 bg-dark opacity-50"><p class="text-center" style="color: white;">Click or drag to change</p></div>

                                        
                                        </div>
                                        <div v-else class="w-100 h-100 bg-dark rounded d-flex align-items-center"><p class="text-center" style="color: white;">Upload the photo</p></div>
                                            
                                        <uc-file-uploader-regular
                                            class="area position-absolute top-0 start-0"
                                        
                                            @dragenter.prevent=""
                                            @dragover.prevent
                                            @drop.prevent="initAttributeFlow"
                                            @dragleave=""
                                            @mouseenter=""
                                            @mouseleave=""
                                            ctx-name="my-attribute-uploader"
                                           headless
                                        >
                                        </uc-file-uploader-regular>

                                    </div>


                                      
                                </div>

                                <div v-if="userAttribute.attribute.type === 'one_of_many'">
                                    <select v-model="userAttribute.value">
                                        <option v-for="option in userAttribute.attribute.options" :key="option" :value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>



                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div v-else class="container d-flex justify-content-center align-items-center min-vh-100">
            <uc-config
                ctx-name="my-avatar-uploader"
                pubkey="6a5a21105b2bdff19cda"
                img-only="true"
                multiple="false"
                use-cloud-image-editor="true"
            ></uc-config>

           <uc-config
                ctx-name="my-attribute-uploader"
                pubkey="6a5a21105b2bdff19cda"
                img-only="true"
                multiple="false"
                use-cloud-image-editor="true"
            ></uc-config>

            <uc-upload-ctx-provider
                id="avatar-uploader-ctx"
                ctx-name="my-avatar-uploader"
               
            ></uc-upload-ctx-provider>
                        
            <uc-upload-ctx-provider
                id="attribute-uploader-ctx"
                ctx-name="my-attribute-uploader"
               
            ></uc-upload-ctx-provider>
            <div class="">
                <span class="spinner-border" style="width: 50px; height: 50px;"></span>
            </div>
        </div>
    
    
    
</template>

<script setup>
import * as UC from '@uploadcare/file-uploader';
import * as UCEditor from '@uploadcare/file-uploader/web/uc-cloud-image-editor.min.js';
import { computed, onMounted, onUnmounted, ref, Transition, watch } from 'vue';
UC.defineComponents(UC);
UCEditor.defineComponents(UCEditor);
import '@uploadcare/file-uploader/web/uc-cloud-image-editor.min.css';
import { useUserProfileStore } from '@/stores/UserProfileStore.js';
import { useAttributeStore } from '@/stores/AttributeStore.js';
let api;
let attributeApi;
import axios from '../services/api.js'
import { useRouter } from 'vue-router';
const isNotLoad = ref(true);
const userProfile = useUserProfileStore();
const isEditing = ref(false);

const isModalAttributesOpen = ref(false);
const attributeStore = useAttributeStore();
const selectedAttributes = ref([]);
const errorMessage = ref('');
const sucessfully = ref(false);
const attributeUrl = ref('');
const router = useRouter();
const version = ref(null);
const isFirstLoad = ref(true);

onMounted(async () => {
    try {
        await userProfile.fetchProfile();
        version.value = userProfile.user.version;
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

    

    if (userProfile.user?.role === 'recruiter'){
        isEditing.value = true;
    }
    
    if (userProfile.user?.role === 'candidate'){
    for (const attribute of userProfile.user.attributes) {
        if (attribute.attribute.type === 'boolean'){
            
            
        } 

        if (attribute.attribute.type === 'period'){
        
            
            
        }
    }
    }
    
    if (!userProfile.user) {
        return;
    }

    if (!userProfile.user?.me?.avatarUrl){
       userProfile.user.me.avatarUrl = "https://static.vecteezy.com/system/resources/previews/069/428/996/large_2x/default-profile-picture-social-media-icon-user-avatar-isolated-symbol-on-white-background-illustration-vector.jpg";
    }
     
    if (userProfile.errorMessage.value){
        console.log(userProfile.errorMessage.value);
        return;
    }
    const ctxAvatar = document.querySelector('#avatar-uploader-ctx');
    api = ctxAvatar.getAPI();
    
    const ctxAttribute = document.querySelector('#attribute-uploader-ctx');
    attributeApi = ctxAttribute.getAPI();
    

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

    attributeApi.on('file-upload-success', (file) => {
        attributeUrl.value = file.cdnUrl;
    })
   }     
);

onUnmounted(()=> {

    }
);

function openOrClosingEditing(){
    if (isEditing.value === false){
        isEditing.value = true;
        return;
    }
    if (isEditing.value === true){
        isEditing.value = false;
        return;
    }
}

function initFlow(){
    api.initFlow();
}

function initAttributeFlow() {
   
    attributeApi.initFlow();
}


const onAvatar = ref(false);

function onMouseEnter() {
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        onAvatar.value = true;
    }
}

function onMouseLeave() {
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        onAvatar.value = false;
    }
}

function onDragEnter() {
    onAvatar.value = true;
}

function onDragLeave() {
    onAvatar.value = false;
}

function handleFile(event){
     
    const file = event.dataTransfer.files[0];
    const state = api.getOutputCollectionState();
    if (state.totalCount > 0){
        api.removeFileByInternalId(state.allEntries[0].internalId);
        
    }
    const entry = api.addFileFromObject(file); 

    api.uploadAll();

}


let timer = null;

const isSave = ref(false);
const errors = ref([]);


const saveProfileData = async () => {
    try{
        const responseAttribute = [];
        userProfile.user.attributes.forEach(attribute => {
            responseAttribute.push({
                'attributeId': attribute.attribute.id,
                'value': attribute.value,
                'options': attribute.attribute.options
            });
        });
        
        const response = {
           'me': userProfile.user.me,
           'attributes': responseAttribute,
           'version': version.value
        }
        console.log("Отправляется: ", response);

        const dataResponse = await axios.patch('/api/profile/me', response);
        version.value = dataResponse.data.version 

        sucessfully.value = true;
        errors.value = [];
    } catch(error) {
        if (error.response?.status === 401) {
            router.push({
                path: '/login',
                query: {
                    error: 'Your session has expired. Please log in again to continue'
                }
            });
        } else if (error.response?.status === 403){
             router.push({
                path: '/login',
                query: {
                    error: 'You do not have the permission to modify this profile'
                }
            });
        } else if (error.response?.status === 409){
            errorMessage.value = error.response.data.message;
        }else if (typeof error.response?.data?.status === 'string'){
            errorMessage.value = error.response.data.status
            errors.value = error.response.data.errors
        } else{
            console.log(error);
            errorMessage.value = "Server error, please try again later";
        }
        return;
    }
}

async function openModalAttributes() {
    await attributeStore.fetchAttribute();

    console.log(attributeStore.attributes);

    isModalAttributesOpen.value = true;
}

const groupedAttributes = computed(() => {
console.log(userProfile.user.attributes);
    const selectedIds = userProfile.user.attributes.map(
        userAttribute => userAttribute.attribute.id
    );

    return (attributeStore.attributes ?? [])
        .filter(attribute => !selectedIds.includes(attribute.id))
        .reduce((groups, attribute) => {
            if (!groups[attribute.category]) {
                groups[attribute.category] = [];
            }

            groups[attribute.category].push(attribute);

            return groups;
        }, {});
});

function addAttribute(attribute){
    if (attribute.type === 'boolean'){
        userProfile.user.attributes.push({
            attribute,
            value: false
        });
    } else if (attribute.type === 'period'){
        userProfile.user.attributes.push({
            attribute,
            value: {
                from: "",
                to: ''
            }
        })
    } else if (attribute.type === 'one_of_many'){
        userProfile.user.attributes.push({
            attribute,
            value: null
        })
    }else if (attribute.type === 'numeric'){
        userProfile.user.attributes.push({
            attribute,
            value: 1  
        })
    } else {
    userProfile.user.attributes.push({
        attribute,
        value: ''
    });
   }
}

function addSelectedAttributes() {
    for (const attribute of selectedAttributes.value) {
        addAttribute(attribute);
    }

    selectedAttributes.value = [];
    isModalAttributesOpen.value = false;
}

function deleteAttribute(attribute){
    const index = userProfile.user.attributes.indexOf(attribute);

    if (index !== -1){
        userProfile.user.attributes.splice(index, 1);
    }
}

function deleteAttributes(){
    for (const attribute of selectedAttributes.value) {
        deleteAttribute(attribute);
    }

    selectedAttributes.value = [];
}

function getAttributeError(attributeId){
    return errors.value.find(
        error => error.attributeId === attributeId
    )
}

function getMeError(field){
    return errors.value.find(
        error => error.field === field
    )?.message
}

function getAttributeUrl(attribute){
    if (attribute.value !== "" && attributeUrl.value === ""){
        return true;
    }
    if (attributeUrl.value !== ""){
        attribute.value = attributeUrl.value;
        return true;
    } else{
        return false;
    }
    
}

watch(() => userProfile.user,
 () => {
    if (timer){
         clearTimeout(timer);
    }

    if (isFirstLoad.value === true){
        isFirstLoad.value = false;
        return;
    }

    timer = setTimeout(()=>{
        if (userProfile.user?.role === 'candidate'){
            saveProfileData()
        }
    }, 5000);
},
  {deep: true});

if (userProfile.user?.role === 'candidate'){
watch(() => userProfile.user.attributes, 
(attributes) => {
    for (const attribute of attributes){
        if (attribute.attribute.type === 'period'){
            if (attribute.value.to < attribute.value.from){
                attribute.value.to = ''
            }
        }
    }
}, {deep: true});
}
</script>

<style scoped>

@media (hover: hover) and (pointer: fine) {
.hint {
    opacity: 0;
    transition: opacity 0.2s ease;
  }

  .avatar-area:hover .hint:hover {
    opacity: 1;
    cursor: pointer;
  }
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.2s ease-in-out;
}

.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateY(20px); 
}

.uc-visual-drop-area{
    height: 200px !important;
}
</style>