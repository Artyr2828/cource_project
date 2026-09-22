<template>
    

<div v-if="userProfile.user?.role === 'recruiter' && userProfile.user !== null" class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Positions</h1>

        <button @click="isModalOpen = true" class="btn btn-primary">
            Create position
        </button>
        
    </div>

    <!--Modal Position-->
    <div v-if="isModalOpen" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" @click.self="isModalAttributesOpen = false">
                    
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Create Position</h5>
            <button
                type="button"
                class="btn-close"
                @click="isModalOpen = false"
            ></button>
        </div>

        <div class="modal-body">
            <div class="mb-3">
                <label for="positionName" class="form-label">
                    Position name
                </label>
                <input
                    id="positionName"
                    type="text"
                    @input="clearErrors('name')"
                    v-model="position.name"
                    :class="{'is-invalid': getPositionError('name')}"
                    class="form-control"
                    placeholder="Enter position name"
                >
                <small v-if="getPositionError('name')" class="small text-danger">{{ getPositionError('name') }}</small> 
            </div>

            <div class="mb-3">
                <label for="positionDescription" class="form-label">
                    Description
                </label>
                <textarea
                    id="positionDescription"
                    @input="clearErrors('description')"
                    v-model="position.description"
                    :class="{'is-invalid': getPositionError('description')}"
                    class="form-control"
                    rows="4"
                    placeholder="Enter position description"
                ></textarea>
                <small v-if="getPositionError('description')" class="small text-danger">{{ getPositionError('description') }}</small> 
            </div>

           
        <div class="mb-3">
            <div class="d-flex justify-content-between">
            <label for="positionAttributesSelected">
                Attributes
            </label>
             <button @click="deleteSelectedAttributes" type="button" class="btn btn-danger btn-sm mb-2">Delete selected</button>
            </div>
        <div
            v-if="position.attributes.length > 0"
            class="mt-3"
        >   
       

        <div v-for="attribute in position.attributes" :key="attribute.id" class="d-flex align-items-center mt-2 border rounded p-2">
            <div class="form-check me-3">
                <input
                    class="form-check-input"
                    type="checkbox"
                    :value="attribute.id"
                    v-model="selectedAttributes"
                >
                
            </div>

            <div>
                <div class="fw-semibold">
                    {{ attribute.name }}
                </div>

                <small class="text-body-secondary">
                    {{ attribute.description }}
                </small>
            </div>
        </div>
    </div>

    <div v-else class="text-body-secondary mt-3">
        No attributes selected
    </div>
</div>



            <button
                type="button"
                class="btn btn-outline-primary"
                @click="openModalAttributes"
            >
                Add attributes
            </button>

            <button
                type="button"
                class="btn btn-warning mt-3 w-100"
                @click="createPosition(position)"
            >
                <span v-if="isLoad" class="spinner-border" style="width: 20px; height: 20px;"></span>
                <span v-else class="text-center fw-bold">Create Position</span>
               
            </button>
            <div class="d-flex justify-content-center">
             <small v-if="successfuly" class="text-success text-center">The position has been successfully created</small>
             <small v-if="errorMessages" class="text-danger">{{ errorMessages }}</small>
             </div>
        </div>
    </div>
</div>


            </div>


            <!--Modal Attributes-->

            <div v-if="isModalAttributesOpen" class="modal fade show d-block" tabindex="-2" style="background-color: rgba(0,0,0,0.5);" @click.self="isModalAttributesOpen = false">
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

    <div class="mb-3">
        <input
            type="text"
            class="form-control"
            placeholder="Search positions..."
        >
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Attributes</th>
                 
                </tr>
            </thead>

            <tbody v-if="positionStore.isLoading === false">
            <tr  v-for="position in positionStore.position.positions" :key="position.id">
                <td>{{ position.name }}</td>
                <td class="text-truncate" style="max-width: 300px;">{{ position.description }}</td>
                <td>{{ position.attributes?.length ?? 0}} </td>
                
            </tr>
            </tbody>
        </table>

        

        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <button v-if="positionStore.position?.hasPrevios === true" @click="previosPage" class="page-link">&lt;</button>
                    <button v-else class="btn page-link opacity-25" disabled>&lt;</button>
                </li>

                <li class="page-item">
                    <button v-if="positionStore.position?.hasNext === true" class="btn page-link" @click="nextPage">&gt;</button>
                    <button v-else class="btn page-link opacity-25" disabled>&gt;</button>
                </li>
            </ul>
        </nav>
    </div>
</div>
    
    <div v-if="userProfile.user?.role === 'candidate' && userProfile.user !== null" class="container">
        <h1>Я кандидат</h1>
    </div>

    <div v-if="userProfile.user === null && userProfile.isLoading === false">
        <h1>Я пользователь</h1>
    </div>

    <div v-if="userProfile.user === null && userProfile.isLoading === true" class="container min-vh-100" style="height: 100px">
        <div class="Loading d-flex justify-content-center align-items-center h-100" >
            <span class="spinner-border"></span>
        </div>
        
    </div>
</template>


<script setup>
import {useUserProfileStore} from '@/stores/UserProfileStore.js'
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAttributeStore } from '@/stores/AttributeStore.js';
import { usePositionStore } from '@/stores/PositionStore.js';
import axios from '../services/api.js';
const attributeStore = useAttributeStore();
const positionStore = usePositionStore();

const router = useRouter();
const userProfile = useUserProfileStore();
const isModalOpen = ref(false);
const isModalAttributesOpen = ref(false);
const selectedAttributes = ref([]);
const errors = ref([]);
const successfuly = ref(false);
const isLoad = ref(false);
const errorMessages = ref('');

const position = ref({
    name: '',
    description: '',
    attributes: []
});


onMounted(async () => {
    await positionStore.fetchPosition(null);
})
async function openModalAttributes(){
    await attributeStore.fetchAttribute();
    isModalAttributesOpen.value = true;
}

const groupedAttributes = computed(() => {
    const selectedIds = position.value.attributes.map(
        positionAttribute => positionAttribute.id
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
    position.value.attributes.push(attribute);
}

function addSelectedAttributes(){
    console.log("Выбранные атрибуты: ", selectedAttributes.value)
    selectedAttributes.value.forEach(element => {
        addAttribute(element);
    }); 
    selectedAttributes.value = [];
    console.log("Position: ", position);
    isModalAttributesOpen.value = false;
}

function getPositionError(field){
    return errors.value.find(
        error => error.field === field
    )?.message
}

function clearErrors(field){
    errors.value = errors.value.filter(
        error => error.field !== field
    );
}

function deleteSelectedAttributes() {
    position.value.attributes = position.value.attributes.filter(
        attribute => !selectedAttributes.value.includes(attribute.id)
    );

    selectedAttributes.value = [];
}

function previosPage(){
    positionStore.fetchPosition(positionStore.position.positions[0].id);
}

function nextPage(){
    const lastPositionId = positionStore.position.positions[positionStore.position.positions.length - 1].id
    positionStore.fetchPosition(lastPositionId);
}

async function createPosition(position){
    isLoad.value = true;
    successfuly.value = false;
    errorMessages.value = '';

    if (position.name.length === 0){
        errors.value.push({
            field: 'name',
            message: 'The position name must not be empty.'
        });
    }
    if (position.description.length === 0){
        errors.value.push({
            field: 'description',
            message: 'The position description must not be empty.'
        });
    }
    if (errors.value.length !== 0){
        isLoad.value = false;
        return;
    }
    try{
        let attributes = []; 
        for (const attribute of position.attributes){
            attributes.push({
                attributeId: attribute.id
            });
        }
        const response = {
            name: position.name,
            description: position.description,
            attributes: attributes
        }
        console.log("Отправляется: ", response);
        await axios.post('/api/position', response);
        successfuly.value = true;
        console.log("Successfuly");
        position.name = "";
        position.description = "";
        positionStore.fetchPosition();
        position.attributes = [];
        
        return true;
    } catch(error){
        if (error.response?.data?.errors){

            error.response.data.errors.forEach(element => {
                errors.value.push(element);
            });
            errorMessages.value = "Error creating the position";
            
        } else if (error.response?.data?.message){
             errorMessages.value = error.response.data.message;
        } else {
            errorMessages.value = "Server error, please try again later";
        }
    } finally {
        isLoad.value = false;
    }
    console.log("Отправляется: ", position);
}

</script>