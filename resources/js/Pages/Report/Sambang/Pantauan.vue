<script setup>
import {Head} from '@inertiajs/vue3';
import Notification from "@/Components/Notification.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Loading from "@/Components/Loading.vue";
import PoldaModel from "@/Models/Polda.model.js";
import {onBeforeMount, onMounted, ref} from "vue";
import EncodeConfig from "@/Configuration/encode.config.js";
import DecodeConfig from "@/Configuration/decode.config.js";
import SambangModel from "@/Models/Sambang.model.js";
import encodeConfig from "@/Configuration/encode.config.js";
import sysConfig from "@/Configuration/sys.config.js";
import decodeConfig from "@/Configuration/decode.config.js";
import WarningButton from "@/Components/WarningButton.vue";
import DangerWarning from "@/Components/DangerWarning.vue";
import AkuTable from "@/Components/AkuTable.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInputArea from "@/Components/TextInputArea.vue";
import ImagePreview from "@/Components/ImagePreview.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SelectDataComponent from "@/Components/Select.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PantauanModel from "@/Models/Pantauan.model.js";
import PageTable from "@/Components/Skeleton/PageTable.vue";
import MultiUploadPhoto from "@/Components/MultiUploadPhoto.vue";
import PrintPopUp from "@/Components/PopUp/PrintPopUp.vue";
import PoldaPopUp from "@/Components/PopUp/PoldaPopUp.vue";

// model
const Model = SambangModel;
const ModelSub = PantauanModel;

// boolean
const isLoading = ref(true)
const rendering = ref(false)
const confirmingUserDeletion = ref(false);
const renderPersonil = ref(true);
const fotoSplit = ref(false)
const popFormUpload = ref(false)

// null
const dummyPhoto = ref(null);

// object
const printModal = ref({
    open: false
});
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
const imagePreview = ref({
    status: false,
    image: null,
})

// array
const columns = [
    {label: 'Action & Foto', field: 'action', search: false, sortable: false, width: '200px'},
    {label: 'Satker', field: 'polda.name', search: true, sortable: true, width: '100px'},
    {label: 'Lokasi', field: 'lokasi', search: true, sortable: true, width: '150px'},
    {label: 'Waktu', field: 'tanggal', search: true, sortable: true, width: '150px'},
    {label: 'Jumlah Personil', field: 'jumlah', search: true, sortable: true, width: '100px'},
    {label: 'Route / Jarak (Nautical Mile)', field: 'jarak', search: true, sortable: true, width: '200px'},
    {label: 'Uraian Giat', field: 'uraian', search: true, sortable: true, width: '150px'},
    {label: 'Jumlah Peserta', field: 'jml_peserta', search: true, sortable: true, width: '150px'},
    {label: 'Ket', field: 'keterangan', search: true, sortable: true, width: '150px'},
]
// Methods
const startup = async () => {
    let check = parseInt(localStorage.getItem('page') ?? 1)
    let encode = EncodeConfig.base64(`{"page":${check},"search":"","perPage":10}`)
    let rawDT = await Model.getPantauanPage(`data=${encode}`)
    bootModel.value = DecodeConfig.base64toJSON(rawDT.data.content)
    rendering.value = true
}
const resetModel = () => {
    fotoSplit.value = false
    dummyPhoto.value = null
    Model.id = null;
    Model.polda_name = '';
    Model.polda_id = null;
    Model.personil_jml = [0,];
    Model.personil_sat = ['personil',];
    Model.lokasi = '';
    Model.jarak = 0;
    Model.uraian = '';
    Model.jml_peserta = 0;
    Model.keterangan = '';
    Model.tipe = 3;
    Model.foto = null;
    Model.foto_original = null;
    let tgl = new Date();
    Model.tanggal = tgl.toISOString().slice(0, 10);
    Model.waktu = (tgl.getHours() < 10 ? '0' : '') + tgl.getHours() + ':' + (tgl.getMinutes() < 10 ? '0' : '') + tgl.getMinutes();
    printModal.value.open = false
}
const addFunction = async () => {
    resetModel()
    let auth = sysConfig.userAuth()
    isLoading.value = true
    Model.polda_id = auth.polda_id === 0 ? '' : auth.polda_id
    Model.polda_name = auth.polda_id === 0 ? '' : auth['polda'].name
    popModal.value.status = true
    popModal.value.title = 'Tambah Data Sambang Nusa Presisi Pantauan'
    isLoading.value = false
}
const editFunction = async (val) => {
    isLoading.value = true
    Model.id = val.id
    Model.polda_id = val.polda_id
    Model.polda_name = val.polda.name
    Model.personil_jml = JSON.parse(val.personil_jml)
    Model.personil_sat = JSON.parse(val.personil_sat)
    Model.lokasi = val.lokasi
    Model.jarak = val.jarak
    Model.uraian = val.uraian
    Model.jml_peserta = val.jml_peserta
    Model.keterangan = val.keterangan
    Model.tipe = 3
    Model.foto = val.foto
    Model.tanggal = val.tanggal
    Model.waktu = val.waktu
    popModal.value.status = true
    popModal.value.title = 'Ubah Data Sambang Nusa Presisi Pantauan'
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
const validation = async () => {
    let modalHeader = document.getElementById('modal-header')
    if (Model.polda_id === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Polda Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.personil_jml.length === 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Jumlah Personil Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.personil_sat.length === 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Satuan Personil Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.tanggal === '') {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Tanggal Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.waktu === '') {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Waktu Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    await save();
}
const save = async () => {
    Model.personil_jml = JSON.stringify(Model.personil_jml)
    Model.personil_sat = JSON.stringify(Model.personil_sat)
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
        Model.id = saveData.data.content.id
        isLoading.value = false;
    } else {
        let modalHeader = document.getElementById('modal-header')
        modalHeader.scrollIntoView({behavior: 'smooth'})
        Model.personil_jml = JSON.parse(Model.personil_jml)
        Model.personil_sat = JSON.parse(Model.personil_sat)
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
const choosePolda = (val) => {
    isLoading.value = true
    Model.polda_id = val.id
    Model.polda_name = val.name
    isLoading.value = false
}
const changePhoto = async (val) => {
    fotoSplit.value = false
    const type = val.target.files[0].type
    const file = val.target.files[0];
    let quality = 0.2
    if (file.size > 1000000) {
        quality = 0.1
    }
    if (file.size < 1000000) {
        quality = 0.11
    }
    if (file.size < 300000) {
        quality = 0.15
    }
    const reader = new FileReader();
    reader.onload = () => {
        const img = new Image();
        img.src = reader.result;
        img.onload = async () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            Model.foto_original = encodeConfig.base64(await sysConfig.ImageBase64Compress(canvas.toDataURL(type), type, 0.7));
            Model.foto = encodeConfig.base64(await sysConfig.ImageBase64Compress(canvas.toDataURL(type), type, quality));
            fotoSplit.value = true
        };
    }
    reader.readAsDataURL(file);
}
const imagePreviewFn = async (e) => {
    isLoading.value = true
    const raw = await Model.getFoto(encodeConfig.base64(e))
    const decodeFoto = decodeConfig.base64toJSON(raw.data.content)
    imagePreview.value.status = true
    imagePreview.value.image = decodeConfig.base64(decodeFoto.foto)
    isLoading.value = false
}
const formatPersonil = (jml, sat) => {
    let jsonJml = JSON.parse(jml)
    let jsonSat = JSON.parse(sat)
    let personil = ''
    jsonJml.map((item, index) => {
        personil += `${item} ${jsonSat[index]} </br>`
    })
    return personil
}
const openUpload = async (val, row = null) => {
    if (val === 1) {
        await validation();
    }else{
        Model.id = row.id
    }
    if (alerts.value.type !== 'danger') {
        popModal.value.status = false
        popFormUpload.value = true
    }
}
const closeUpload = async () => {
    rendering.value = false
    popFormUpload.value = false
    await startup()
}
onBeforeMount(async () => {
    resetModel()
    await startup()
})

onMounted(() => {
    isLoading.value = false
})
</script>

<template>
    <Head title="Pantauan"/>

    <div class="mx-auto sm:px-6 lg:px-8">
        <Notification :alerts="alerts"/>
        <page-table v-if="!rendering"/>
        <div v-else>
            <div class="md:px-3 md:py-4 flex flex-col md:flex-row gap-1.5 md:gap-1 md:justify-end">
                <success-button class="pl-2 pr-3" @click="addFunction">
                    <font-awesome-icon icon="fa-solid fa-plus" class="mx-1"/>
                    Add
                </success-button>
                <primary-button class="pl-2 pr-3" @click="printModal.open = true">
                    <font-awesome-icon icon="fa-solid fa-print" class="mx-1"/>
                    Export
                </primary-button>
            </div>
            <AkuTable
                v-if="rendering"
                :column="columns"
                :data-table="bootModel"
                :select-options="{
                        enabled:false
                    }"
                :model="ModelSub"
                :search-active="{
                        selectActive:false,
                        enabled:true
                    }"
                checkbox-options
                ref="datatable"
            >
                <template #table-row="props">
                                <span v-if="props.column.field === 'jumlah'">
                                  <span class="capitalize"
                                        v-html=" formatPersonil(props.row.personil_jml, props.row.personil_sat)"/>
                                </span>
                                <span v-if="props.column.field === 'jarak'">
                                              <span class="capitalize">{{ props.row.jarak }} NM</span>
                                            </span>
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
                                    <success-button class="pl-2 mt-4 pr-3" @click="openUpload(0,props.row)">
                                        <font-awesome-icon icon="fa-solid fa-eye" class="mx-1"/>
                                          Foto <sup><span class="inline-flex items-center justify-center w-4 h-4 text-xs font-semibold text-blue-800 bg-yellow-200 rounded-full">
                                                    {{ props.row.foto_count }}
                                                </span></sup>
                                    </success-button>
                                </span>
                </template>
            </AkuTable>
        </div>
    </div>

    <Modal :show="popModal.status" @close="closeModal" max-width="4xl">
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
                        <InputLabel value="Tanggal & Waktu"/>
                        <div class="grid grid-cols-2 gap-2">
                            <TextInput
                                type="date"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.tanggal"
                                placeholder="Tanggal"
                            />
                            <TextInput
                                type="time"
                                class="mt-1 block w-full capitalize"
                                v-model="Model.waktu"
                                placeholder="Waktu"
                            />
                        </div>
                    </div>
                    <div class="mt-4">
                        <polda-pop-up v-if="!isLoading" @choosePolda="choosePolda" :polda-id="Model.polda_id" :polda-name="Model.polda_name" title="Polda" placeholder="Polda"/>
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Personil"/>
                        <div class="rounded border px-2 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <InputLabel class="px-2 font-bold" value="Jumlah Personil"/>
                                <InputLabel class="px-2 font-bold" value="Personil"/>
                            </div>
                            <div v-if="renderPersonil" v-for="(personil, index) in Model.personil_jml"
                                 class="grid grid-cols-2 gap-2"
                            >
                                <TextInput
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full capitalize"
                                    v-model="Model.personil_jml[index]"
                                    placeholder="Jumlah personil"
                                />
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full capitalize"
                                    v-model="Model.personil_sat[index]"
                                    placeholder="satuan ( Personil, Orang, Kompi )"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Lokasi"/>
                        <TextInputArea
                            class="mt-1 block w-full capitalize"
                            v-model="Model.lokasi"
                            placeholder="Lokasi ..."
                        />
                    </div>
                    <hr class="mt-4"/>
                    <div class="mt-4">
                        <InputLabel value="Route / Jarak"/>
                        <TextInput
                            type="number"
                            class="mt-1 block w-full capitalize"
                            v-model="Model.jarak"
                            placeholder="Jarak ( KM )"
                        />
                    </div>
                    <hr class="mt-4"/>
                    <div class="mt-4">
                        <InputLabel value="Uraian Giat"/>
                        <TextInputArea
                            type="text"
                            class="mt-1 block w-full capitalize"
                            v-model="Model.uraian"
                            placeholder="Laporan Uraian Kegiatan"
                        />
                    </div>
                    <hr class="mt-4"/>
                    <div class="mt-4">
                        <InputLabel value="Jumlah Peserta"/>
                        <TextInput
                            type="number"
                            class="mt-1 block w-full capitalize"
                            v-model="Model.jml_peserta"
                            placeholder="Jumlah Peserta"
                        />
                    </div>
                    <hr class="mt-4"/>
                    <div class="mt-4">
                        <InputLabel value="Keterangan"/>
                        <TextInputArea
                            type="text"
                            class="mt-1 block w-full capitalize"
                            v-model="Model.keterangan"
                            placeholder="Keterangan"
                        />
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="px-4 py-2 flex justify-end border-t gap-2 rounded-b bg-gray-100 ">
                    <SecondaryButton @click="openUpload(1)">Upload Foto</SecondaryButton>
                    <primary-button @click="validation()">
                        Save
                    </primary-button>
                </div>
            </div>
        </div>
    </Modal>

    <MultiUploadPhoto :show="popFormUpload" @close="closeUpload" :model="Model"/>

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

    <PrintPopUp
        :show="printModal.open"
        tipe="3"
        @close="printModal.open = false"
        title="Print Sambang Nusa Presisi"
        urlPrint="/api/report/sambang/download"
    />

    <image-preview max-width="7xl" :show="imagePreview.status" @close="imagePreview.status = false">
        <img :src="imagePreview.image" alt="Bonnie image" class="w-full"/>
    </image-preview>
    <loading :loading="isLoading"/>
</template>
