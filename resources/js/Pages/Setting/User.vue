<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import UserModel from "@/Models/User.model.js";
import EncodeConfig from "@/Configuration/encode.config.js";
import {onBeforeMount, onMounted, ref} from "vue";
import DecodeConfig from "@/Configuration/decode.config.js";
import AkuTable from "@/Components/AkuTable.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import DangerWarning from "@/Components/DangerWarning.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import Notification from "@/Components/Notification.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import sysConfig from "@/Configuration/sys.config.js";
import RoleModel from "@/Models/Role.model.js";
import Select from "@/Components/Select.vue";
import PoldaModel from "@/Models/Polda.model.js";
import PoldaPopUp from "@/Components/PopUp/PoldaPopUp.vue";

// Variables

// model
const Model = UserModel;
const ModelRole = RoleModel;

// boolean
const isLoading = ref(true)
const rendering = ref(false)
const confirmingUserDeletion = ref(false);
const modalEdit = ref(false);

// object
const userAuth = ref(sysConfig.userAuth());
const popModal = ref({
    status: false,
    title: '',
})
const bootModel = ref({})
const alerts = ref({
    status: false,
    message: '',
    type: 'info',
})

// array
const arrRole = ref([])
const columns = [
    {label: 'Name', field: 'name', search: true, sortable: true, width: 'auto'},
    {label: 'Username', field: 'username', search: true, sortable: true, width: 'auto'},
    {label: 'Email', field: 'email', search: true, sortable: true, width: 'auto'},
    {label: 'Role', field: 'role.name', search: true, sortable: true, width: 'auto'},
    {label: 'Access', field: 'polda_id', search: true, sortable: true, width: 'auto'},
    {label: 'Action', field: 'action', search: false, sortable: false, width: 'auto'},
]

// Methods
const resetModel = () =>{
    confirmingUserDeletion.value = false
    Model.id = null
    Model.name = ''
    Model.username = ''
    Model.email = ''
    Model.password = null
    Model.password_confirmation = null
    Model.role_id = null
    Model.role_name = ''
    Model.polda_id = null
    Model.polda_name = ''
    arrRole.value = []
}
const startup = async () => {
    let check = parseInt(localStorage.getItem('page') ?? 1)
    let encode = EncodeConfig.base64(`{"page":${check},"search":"","perPage":10}`)
    let rawDT = await Model.getPage(`data=${encode}`)
    bootModel.value = DecodeConfig.base64toJSON(rawDT.data.content)
    rendering.value = true
}
const getRole = async () => {
    let rawRole = await ModelRole.getAll()
    let decodeRole = DecodeConfig.base64toJSON(rawRole.data.content)
    arrRole.value = decodeRole.map((item) => {
        return {
            id: item.id,
            name: item.name
        }
    })
}
const addFunction = async () => {
    resetModel()
    await getRole()
    let auth = sysConfig.userAuth()
    isLoading.value = true
    Model.polda_id = auth.polda_id === 0 ? '' : auth.polda_id
    Model.polda_name = auth.polda_id === 0 ? '' : auth['polda'].name
    popModal.value.status = true
    popModal.value.title = 'Tambah User'
    modalEdit.value = false
    isLoading.value = false
}
const editFunction = async (val) => {
    isLoading.value = true
    Model.id = val.id
    Model.name = val.name
    Model.username = val.username
    Model.email = val.email
    Model.password = null
    Model.password_confirmation = null
    Model.role_id = val.role_id
    Model.polda_id = val.polda_id
    Model.polda_name = val.polda_id === 0 ? 'SEMUA POLDA' : val.polda.name
    await getRole()
    popModal.value.status = true
    popModal.value.title = 'Ubah User'
    modalEdit.value = true
    isLoading.value = false
}
const confirmUserDeletion = (val) => {
    Model.id = val.id
    confirmingUserDeletion.value = true;
};
const DeleteFn = async () => {
    isLoading.value = true;
    rendering.value = false
    let encode = EncodeConfig.base64(JSON.stringify(Model.id))
    let deleteData = await Model.destroy(encode);
    if (deleteData.data.status) {
        alerts.value.status = true
        alerts.value.message = 'Data Berhasil Dihapus'
        alerts.value.type = 'success'
        closeModal()
        await startup()
        isLoading.value = false;
        rendering.value = true
    } else {
        isLoading.value = false
        rendering.value = true
        closeModal()
        alerts.value.status = true
        alerts.value.message = deleteData.data.message
        alerts.value.type = 'danger'
    }
}
const validation = () => {
    let modalHeader = document.getElementById('modal-header')
    if (Model.name === '') {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Nama Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.polda_id === null || Model.polda_name === '' ) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Access Polda Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.username === '') {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Username Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.email === '') {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Email Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.password === null && modalEdit.value === false) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Password Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.password_confirmation === null && modalEdit.value === false) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Konfirmasi Password Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.password !== Model.password_confirmation && modalEdit.value === false) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Password Tidak Sama'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.role_id === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Role Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    save();
}
const save = async () => {
    isLoading.value = true
    rendering.value = false
    let encode = EncodeConfig.base64(JSON.stringify(Model))
    let saveData = await Model.createUpdate(`data=${encode}`)
    if (saveData.data.status) {
        alerts.value.status = true;
        alerts.value.message = 'Data Berhasil Disimpan';
        alerts.value.type = 'success';
        window.scrollTo(0, 0);
        closeModal()
        await startup()
        isLoading.value = false;
    } else {
        let modalHeader = document.getElementById('modal-header')
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = saveData.data.message
        alerts.value.type = 'danger'
        rendering.value = true
        isLoading.value = false
    }
}
const closeModal = () => {
    resetModel()
    popModal.value.status = false
}
const choosePolda = (val) => {
    isLoading.value = true
    Model.polda_id = val.id
    Model.polda_name = val.name
    isLoading.value = false
}

// Lifecycle
onBeforeMount(async () => {
    await startup()
})

onMounted(() => {
    isLoading.value = false
})
</script>

<template>
    <Head title="User"/>

    <AuthenticatedLayout :props-loading="isLoading">
        <template #header>
            User Management
        </template>

        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Modal notification -->
                <Notification :alerts="alerts"/>
                <div class="md:px-3 md:py-4 flex flex-col md:flex-row gap-1.5 md:gap-1 md:justify-end">
                    <success-button class="pl-2 pr-3" @click="addFunction">
                        <font-awesome-icon icon="fa-solid fa-plus" class="mx-1"/>
                        Add
                    </success-button>
                </div>
                <AkuTable
                    v-if="rendering"
                    :column="columns"
                    :data-table="bootModel"
                    :select-options="{
                        enabled:false
                    }"
                    :model="Model"
                    :search-active="{
                        selectActive:false,
                        enabled:true
                    }"
                    checkbox-options
                    ref="datatable"
                >
                    <template #table-row="props">
                                <span v-if="props.column.field === 'name'">
                                  <span class="capitalize">{{ props.row.name }}</span>
                                </span>
                                <span v-if="props.column.field === 'polda_id'">
                                  <span class="capitalize">{{ props.row.polda_id === 0 ? 'SEMUA POLDA' : props.row.polda.name }}</span>
                                </span>
                        <span v-if="props.column.field === 'action'">
                                    <span class="flex gap-2">
                                        <secondary-button v-if="userAuth.id >= props.row.id" :disabled="true"
                                                          class="pl-2 pr-3">
                                            <font-awesome-icon icon="fa-solid fa-pencil" class="mx-1"/>
                                            Edit
                                        </secondary-button>
                                        <warning-button v-else class="pl-2 pr-3" @click="editFunction(props.row)">
                                            <font-awesome-icon icon="fa-solid fa-pencil" class="mx-1"/>
                                            Edit
                                        </warning-button>
                                        <secondary-button v-if="userAuth.id >= props.row.id" :disabled="true"
                                                          class="pl-2 pr-3">
                                            <font-awesome-icon icon="fa-solid fa-trash" class="mx-1"/>
                                            Delete
                                        </secondary-button>
                                        <danger-warning v-else class="pl-2 pr-3"
                                                        @click="confirmUserDeletion(props.row)">
                                            <font-awesome-icon icon="fa-solid fa-trash" class="mx-1"/>
                                            Delete
                                        </danger-warning>
                                    </span>
                                </span>
                    </template>
                </AkuTable>
            </div>
        </div>

        <Modal :show="popModal.status" @close="closeModal">
            <div class="relative">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <button @click="closeModal" type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-red-300 hover:text-red-900 rounded-lg text-sm px-2.5 py-2 ml-auto inline-flex items-center"
                    >
                        <font-awesome-icon icon="fa-solid fa-close"/>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <!-- Modal header -->
                    <div id="modal-header" class="px-6 py-4 border-b rounded-t bg-gray-100">
                        <h3 class="text-base font-semibold text-gray-900 lg:text-xl ">
                            {{ popModal.title }}
                        </h3>
                    </div>

                    <!-- Modal notification -->
                    <Notification :alerts="alerts"/>

                    <!-- Modal body -->
                    <div class="p-6">
                        <div class="mt-4">
                            <InputLabel value="Nama"/>
                            <TextInput
                                type="text"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.name"
                                placeholder="Nama"
                            />
                        </div>
                        <div class="mt-4">
                            <polda-pop-up v-if="!isLoading" @choosePolda="choosePolda" all-polda :polda-id="Model.polda_id" :polda-name="Model.polda_name" title="Access Polda" placeholder="Access"/>
                        </div>
                        <div class="mt-4">
                            <InputLabel value="UserName"/>
                            <TextInput
                                type="text"
                                class="mt-1 block w-full"
                                v-model="Model.username"
                                placeholder="UserName"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Email"/>
                            <TextInput
                                type="email"
                                class="mt-1 block w-full"
                                v-model="Model.email"
                                placeholder="Email"
                            />
                        </div>
                        <primary-button v-if="modalEdit" class="mt-4" @click="modalEdit = false">Change Password</primary-button>
                        <div v-if="!modalEdit">
                            <div class="mt-4">
                                <InputLabel value="Password"/>
                                <TextInput
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="Model.password"
                                    min="8"
                                    placeholder="********"
                                />
                            </div>
                            <div class="mt-4">
                                <InputLabel value="Password Confirm"/>
                                <TextInput
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="Model.password_confirmation"
                                    min="8"
                                    placeholder="********"
                                />
                            </div>
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Role"/>
                            <Select :model-value="Model.role_id" @update:model-value="Model.role_id = $event"
                                    :data="arrRole"/>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="px-4 py-2 flex justify-end border-t rounded-b bg-gray-100 ">
                        <primary-button @click="validation()">
                            Save
                        </primary-button>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus data ini?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Data ini tidak dapat dikembalikan
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': isLoading }"
                        :disabled="isLoading"
                        @click="DeleteFn"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
