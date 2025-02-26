<script setup>
import {computed, onMounted, onUnmounted, ref, watch} from 'vue';
import encodeConfig from "@/Configuration/encode.config.js";
import Notification from "@/Components/Notification.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Loading from "@/Components/Loading.vue";
import PoldaPopUp from "@/Components/PopUp/PoldaPopUp.vue";
import sysConfig from "@/Configuration/sys.config.js";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    urlPrint: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        default: 'Print',
    },
    tipe:{
        type: Number,
        default: 1
    },
    maxWidth: {
        type: String,
        default: '4xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

// Variable

const isLoading = ref(false)

const arrHari = ref([
    'MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'
])
const arrBulan = ref([
    {value: 0, label: 'JANUARI'},
    {value: 1, label: 'FEBRUARI'},
    {value: 2, label: 'MARET'},
    {value: 3, label: 'APRIL'},
    {value: 4, label: 'MEI'},
    {value: 5, label: 'JUNI'},
    {value: 6, label: 'JULI'},
    {value: 7, label: 'AGUSTUS'},
    {value: 8, label: 'SEPTEMBER'},
    {value: 9, label: 'OKTOBER'},
    {value: 10, label: 'NOVEMBER'},
    {value: 11, label: 'DESEMBER'},
])
const alerts = ref({
    status: false,
    message: '',
    type: 'info',
})

const printModal = ref({
    open: false,
    title: '',
    periode: '1',
    polda_id: null,
    polda_name: '',
    namaBulan: '',
    namaHari: '',
    tglDari: '',
    tglSampai: '',
    tgl: '',
    tipe: '',
    bulan: '',
    tahun: '',
    formatExport: 'xlsx',
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            booting()
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const resetModel = () => {
    let auth = sysConfig.userAuth()
    let tanggal = new Date()
    printModal.value.open = false
    printModal.value.tglSampai = ''
    printModal.value.tglDari = ''
    printModal.value.title = props.title
    printModal.value.formatExport = 'pdf'
    printModal.value.periode = '1'
    printModal.value.polda_id = auth.polda_id === 0 ? null : auth.polda_id
    printModal.value.polda_name = auth.polda_id === 0 ? '' : auth["polda"].name
    printModal.value.namaBulan = arrBulan.value[tanggal.getMonth()].label
    printModal.value.namaHari = arrHari.value[tanggal.getDay()]
    printModal.value.tgl = tanggal.getDate()
    printModal.value.tipe = props.tipe
    printModal.value.bulan = tanggal.getMonth()
    printModal.value.tahun = tanggal.getFullYear()
}

const booting = async () => {
    isLoading.value = true;
    let tanggal = new Date()
    resetModel()
    printModal.value.namaBulan = arrBulan.value[tanggal.getMonth()].label
    isLoading.value = false;
};

const validation = async () => {
    let encode = encodeConfig.base64(JSON.stringify(printModal.value))
    // let urel = window.location.protocol + '//' + window.location.host + props.urlPrint + '/check?data=' + encode
    let data = fetch(props.urlPrint + '/check?data=' + encode, {method: 'GET'})
    let response = await data
    let result = await response.json()
    if (!result.status) {
        alerts.value.status = true
        alerts.value.message = result.message
        alerts.value.type = 'danger'
        isLoading.value = false
        return false
    }
    await fetch(window.location.protocol + '//' + window.location.host + props.urlPrint + '?data=' + encode, {method: 'GET'})
        .then(response => response.blob())
        .then(blob => URL.createObjectURL(blob))
        .then(url => {
            let link = document.createElement("a");
            link.href = url;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    isLoading.value = false;
}

const actionPrint = async () => {
    isLoading.value = true
    let bulan = printModal.value.bulan < 10 ? '0' + (printModal.value.bulan + 1) : (printModal.value.bulan + 1)
    let tgl = printModal.value.tgl < 10 ? '0' + printModal.value.tgl : printModal.value.tgl
    if (printModal.value.periode === '0') {
        printModal.value.tglDari = printModal.value.tahun + '-' + bulan + '-' + tgl
        printModal.value.tglSampai = printModal.value.tahun + '-' + bulan + '-' + tgl
    } else if (printModal.value.periode === '1') {
        printModal.value.tglDari = printModal.value.tahun + '-' + bulan + '-01'
        let tg = new Date(printModal.value.tglDari), y = tg.getFullYear(), m = tg.getMonth() + 1;
        let lastDay = new Date(y, m, 0);
        printModal.value.tglSampai = printModal.value.tahun + '-' + bulan + '-' + lastDay.getDate()
    } else if (printModal.value.periode === '2') {
        printModal.value.tglDari = printModal.value.tahun + '-01-01'
        printModal.value.tglSampai = printModal.value.tahun + '-12-31'
    }
    let tg = new Date(printModal.value.tglDari)
    printModal.value.namaHari = arrHari.value[tg.getDay()]
    await validation()
}

const choosePolda = (val) => {
    isLoading.value = true
    printModal.value.polda_id = val.id
    printModal.value.polda_name = val.name
    isLoading.value = false
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
        '6xl': 'sm:max-w-6xl',
        '7xl': 'sm:max-w-7xl',
        'full': 'sm:max-w-full h-screen',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 items-center justify-center">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-500/50 backdrop-blur-lg"/>
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="my-6 bg-white rounded-lg shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <div class="relative">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <button @click="close" type="button"
                                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                >
                                    <font-awesome-icon icon="fa-solid fa-close"/>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <!-- Modal header -->
                                <div class="px-6 py-4 border-b rounded-t bg-gray-100">
                                    <h3 class="text-base font-semibold text-gray-900 lg:text-xl ">
                                        {{ printModal.title }}
                                    </h3>
                                </div>

                                <!-- Modal notification -->
                                <Notification :alerts="alerts"/>

                                <!-- Modal body -->
                                <div class="p-6">
                                    <div class="mt-4">
                                        <polda-pop-up v-if="!isLoading" @choosePolda="choosePolda" :polda-id="printModal.polda_id" :polda-name="printModal.polda_name" title="Polda" placeholder="Polda"/>
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel value="Periode"/>
                                        <select
                                            class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            v-model="printModal.periode"
                                        >
                                            <option value="0">Per Hari</option>
                                            <option value="1">Per Bulan</option>
                                            <option value="2">Per Tahun</option>
                                        </select>
                                        <div class="w-full mt-2 grid gap-2"
                                             :class=" printModal.periode === '0' ? 'grid-cols-3' : (printModal.periode === '1' ? 'grid-cols-2' : 'grid-cols-1')">

                                            <select
                                                v-if="printModal.periode === '0'"
                                                class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                v-model="printModal.tgl"
                                            >
                                                <option v-for="tng in 31" :key="tng" :value="tng">{{ tng }}</option>
                                            </select>

                                            <select
                                                v-if="printModal.periode !== '2'"
                                                class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                v-model="printModal.bulan"
                                                @change="printModal.namaBulan = arrBulan.find((item) => item.value === $event.target.value)['label']"
                                            >
                                                <option v-for="month in arrBulan" :key="month.value"
                                                        :value="month.value">
                                                    {{ month.label }}
                                                </option>
                                            </select>

                                            <text-input v-model="printModal.tahun" type="number"/>

                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel value="Format"/>
                                        <div class="relative w-full">
                                            <select
                                                class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                v-model="printModal.formatExport"
                                            >
                                                <option value="xlsx">XLSX</option>
                                                <option value="xls">XLS</option>
                                                <option value="pdf">PDF</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="px-4 py-2 flex justify-end border-t rounded-b bg-gray-100 ">
                                    <primary-button @click="actionPrint()">
                                        Export
                                    </primary-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
    <Loading :loading="isLoading"/>
</template>
