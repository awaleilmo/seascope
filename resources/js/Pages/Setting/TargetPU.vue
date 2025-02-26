<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
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
import TargetModel from "@/Models/Target.model.js";

// Variables

// model
const Model = TargetModel;

// boolean
const isLoading = ref(true)
const rendering = ref(false)
const confirmingUserDeletion = ref(false);

// object
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
const columns = [
    {label: 'Tahun', field: 'tahun', search: true, sortable: true, width: 'auto'},
    {label: 'Klinik', field: 'klinik', search: true, sortable: true, width: 'auto'},
    {label: 'Perpustakaan', field: 'perpustakaan', search: true, sortable: true, width: 'auto'},
    {label: 'Sambang Nusa', field: 'sambang', search: true, sortable: true, width: 'auto'},
    {label: 'Sapu Bersih Sampah', field: 'sapu', search: true, sortable: true, width: 'auto'},
    {label: 'Polisi RW', field: 'rw', search: true, sortable: true, width: 'auto'},
    {label: 'Action', field: 'action', search: false, sortable: false, width: '250px'},
]

// Methods
const resetModel = () => {
    Model.id = null
    Model.klinik = ''
    Model.perpustakaan = ''
    Model.rw = ''
    Model.sambang = ''
    Model.sapu = ''
    let tgl = new Date()
    Model.tahun = tgl.getFullYear()
}
const startup = async () => {
    let check = parseInt(localStorage.getItem('page') ?? 1)
    let encode = EncodeConfig.base64(`{"page":${check},"search":"","perPage":10}`)
    let rawDT = await Model.getPage(`data=${encode}`)
    bootModel.value = DecodeConfig.base64toJSON(rawDT.data.content)
    rendering.value = true
}
const addFunction = () => {
    resetModel()
    isLoading.value = true
    popModal.value.status = true
    popModal.value.title = 'Tambah Target Program Unggulan'
    isLoading.value = false
}
const editFunction = (val) => {
    isLoading.value = true
    Model.id = val.id
    Model.klinik = val.klinik
    Model.perpustakaan = val.perpustakaan
    Model.rw = val.rw
    Model.sambang = val.sambang
    Model.sapu = val.sapu
    Model.tahun = val.tahun
    popModal.value.status = true
    popModal.value.title = 'Ubah Target Program Unggulan'
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
    if (Model.tahun === '') {
        alerts.value.status = true
        alerts.value.message = 'Tahun Tidak Boleh Kosong'
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
        closeModal()
        await startup()
        isLoading.value = false;
    } else {
        alerts.value.status = true
        alerts.value.message = saveData.data.message
        alerts.value.type = 'danger'
        rendering.value = true
        isLoading.value = false
    }
}
const closeModal = () => {
    popModal.value.status = false
    confirmingUserDeletion.value = false
    resetModel()
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
    <Head title="Target Program Unggulan"/>

    <AuthenticatedLayout :props-loading="isLoading">
        <template #header>
            Target Program Unggulan
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
                        <span v-if="props.column.field === 'action'">
                                    <span class="flex gap-2">
                                        <warning-button class="pl-2 pr-3" @click="editFunction(props.row)">
                                            <font-awesome-icon icon="fa-solid fa-pencil" class="mx-1"/>
                                            Edit
                                        </warning-button>
                                        <danger-warning class="pl-2 pr-3" @click="confirmUserDeletion(props.row)">
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
                    <div class="px-6 py-4 border-b rounded-t bg-gray-100">
                        <h3 class="text-base font-semibold text-gray-900 lg:text-xl ">
                            {{ popModal.title }}
                        </h3>
                    </div>

                    <!-- Modal notification -->
                    <Notification :alerts="alerts"/>

                    <!-- Modal body -->
                    <div class="p-6">
                        <div class="mt-4">
                            <InputLabel value="Tahun"/>
                            <TextInput
                                type="number"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.tahun"
                                placeholder="Tahun"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Klinik"/>
                            <TextInput
                                type="number"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.klinik"
                                placeholder="Klinik"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Perpustakaan"/>
                            <TextInput
                                type="number"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.perpustakaan"
                                placeholder="Perpustakaan"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Sambang Nusa"/>
                            <TextInput
                                type="number"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.sambang"
                                placeholder="Sambang Nusa"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Sapu Bersih Sampah"/>
                            <TextInput
                                type="number"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.sapu"
                                placeholder="Sapu Bersih Sampah"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Polisi RW"/>
                            <TextInput
                                type="number"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.rw"
                                placeholder="Polisi RW"
                            />
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
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
