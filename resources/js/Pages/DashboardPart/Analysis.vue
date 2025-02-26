<script setup>
import {onBeforeMount, ref, watchEffect} from "vue";
import {initFlowbite} from "flowbite";
import GraphicModel from "@/Models/Graphic.model.js";
import DecodeConfig from "@/Configuration/decode.config.js";
import PoldaModel from "@/Models/Polda.model.js";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import TextInput from "@/Components/TextInput.vue";
import FloatLabel from "@/Components/FloatLabel.vue";
import Select from "@/Components/Select.vue";
import EncodeConfig from "@/Configuration/encode.config.js";
import encodeConfig from "@/Configuration/encode.config.js";

// Variable
const Model = GraphicModel
const formModel = ref({
    id: null,
    tanggal: '',
    tahun: '',
    bulan: '%%',
    lokasi_id: '%%',
    lokasi_name: 'Semua Lokasi',
    polda_id: '%%',
    polda_name: 'Semua Polda',
    jml_perpus: '',
    jml_klinik: '',
    jml_organik: '',
    jml_anorganik: '',
})
const ModelPolda = PoldaModel
const isLoading = ref(true)
const isLoadingPerpus = ref(true)
const isLoadingKlinik = ref(true)
const isLoadingSampah = ref(true)
const arrBulan = ref([
    {id: '%%', name: 'SEMUA'},
    {id: '1', name: 'JANUARI'},
    {id: '2', name: 'FEBRUARI'},
    {id: '3', name: 'MARET'},
    {id: '4', name: 'APRIL'},
    {id: '5', name: 'MEI'},
    {id: '6', name: 'JUNI'},
    {id: '7', name: 'JULI'},
    {id: '8', name: 'AGUSTUS'},
    {id: '9', name: 'SEPTEMBER'},
    {id: '10', name: 'OKTOBER'},
    {id: '11', name: 'NOVEMBER'},
    {id: '12', name: 'DESEMBER'},
])
const noData = ref(false)
const arrPolda = ref([])
const arrBkPolda = ref([])
const popoverPolda = ref(false);
const booting = ref(true)

// Methods
const startUp = async () => {
    let date = new Date();
    formModel.value.tahun = date.getFullYear()
    formModel.value.bulan = '%%'
    formModel.value.lokasi_id = '%%'
    await updateData()
    await getPolda()
    isLoading.value = false
    booting.value = false
}
const getPolda = async () => {
    let rawPolda = await ModelPolda.getAll()
    let decode = DecodeConfig.base64toJSON(rawPolda.data.content)
    arrPolda.value = decode.map((item) => {
        return {
            id: item.id,
            name: item.name,
        }
    })
    arrBkPolda.value = arrPolda.value
}
const updateData = () => {
    updateDataSampah()
    updateDataPerpus()
    updateDataKlinik()
}
const updateDataPerpus = async () => {
    isLoadingPerpus.value = true
    let encode = encodeConfig.base64(JSON.stringify(formModel.value))
    let dataRaw = await Model.perpustakaan(encode)
    let data = DecodeConfig.base64toJSON(dataRaw.data.content)
    formModel.value.jml_perpus = '0'
    if (data['jml_peserta'] !== null) {
        formModel.value.jml_perpus = data['jml_peserta']
    }
    isLoadingPerpus.value = false
}
const updateDataKlinik = async () => {
    isLoadingKlinik.value = true
    let encode = encodeConfig.base64(JSON.stringify(formModel.value))
    let dataRaw = await Model.klinik(encode)
    let data = DecodeConfig.base64toJSON(dataRaw.data.content)
    formModel.value.jml_klinik = '0'
    if (data['jml_peserta'] !== null) {
        formModel.value.jml_klinik = data['jml_peserta']
    }
    isLoadingKlinik.value = false
}
const updateDataSampah = async () => {
    isLoadingSampah.value = true
    let encode = encodeConfig.base64(JSON.stringify(formModel.value))
    let dataRaw = await Model.sampah(encode)
    let data = DecodeConfig.base64toJSON(dataRaw.data.content)
    formModel.value.jml_organik = '0'
    formModel.value.jml_anorganik = '0'
    if (data['organik'] !== null) {
        formModel.value.jml_organik = data['organik']
    }
    if (data['anorganik'] !== null) {
        formModel.value.jml_anorganik = data['anorganik']
    }
    isLoadingSampah.value = false
}
const changeInput = async () => {
    await updateData()
}
const choosePolda = async (val) => {
    if (val.id === '%%') {
        arrPolda.value = arrBkPolda.value
        formModel.value.lokasi_id = '%%'
        formModel.value.polda_name = 'Semua Polda'
        formModel.value.polda_id = '%%'
        popoverPolda.value = false
        await changeInput()
        return
    }
    formModel.value.polda_id = val.id
    formModel.value.polda_name = val.name
    let encode = EncodeConfig.base64(`{"id":${val.id}}`)
    let dataRawPolda = await ModelPolda.getById(encode)
    let decode = DecodeConfig.base64toJSON(dataRawPolda.data.content)
    formModel.value.lokasi_id = decode.lokasi_id
    popoverPolda.value = false
    await changeInput()
}
const poldaSearch = () => {
    if (arrPolda.value.length >= 0) {
        arrPolda.value = arrBkPolda.value.filter(item => item['name'].toLowerCase().indexOf(formModel.value.polda_name.toLowerCase()) > -1)
    }
}
const convertKgToTon = (val) => {
    let parsed = parseFloat(val)
    if (!isNaN(parsed)) {
        if (parsed > 1000) {
            return (parsed / 1000).toFixed(1) + ' Ton'
        }
        return parsed.toFixed(1) + ' Kg'
    }
}
// Lifecycle
onBeforeMount(() => {
    initFlowbite()
    startUp()
})
watchEffect(() => {
    if (!booting.value && !isLoading.value) {
        setInterval(() => {
            updateData()
        }, 60000)
    }
})
</script>

<template>
    <div v-if="isLoading" role="status"
         class="p-4 h-fit border border-gray-200 bg-white rounded shadow animate-pulse md:p-6">
        <div class="flex justify-between">
            <div>
                <h5 class="h-7 bg-gray-200 rounded mb-2.5"></h5>
                <p class="w-48 h-2 bg-gray-200 rounded-full"></p>
            </div>
            <div
                class="flex items-center px-2.5 py-0.5 gap-1 text-base font-semibold text-green-500 text-center">
                <div class="h-10 bg-gray-200 rounded w-[250px]"></div>
                <div class="h-10 bg-gray-200 rounded w-[120px]"></div>
                <div class="h-10 bg-gray-200 rounded w-[120px]"></div>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 mt-4">
            <div v-for="i in 4" class=" mb-2 bg-gray-200 rounded-lg h-28"></div>
        </div>
        <span class="sr-only">Loading...</span>
    </div>


    <div v-if="!isLoading"
         class="w-full h-fit bg-gray-700 rounded-lg shadow hover:shadow-xl transition-all p-1 md:p-6">

        <div class="flex justify-between">
            <div>
                <h5 class="leading-none text-2xl font-bold text-white pb-2">Activity Display
                    {{ formModel.polda_id === '%%' ? '' : formModel.polda_name }}</h5>
                <p class="text-base font-normal text-gray-300">Tahun {{ formModel.tahun }}</p>
            </div>
            <div
                class="flex items-center px-0 py-0 gap-1 text-base font-semibold text-green-500 text-center">
                <div class="relative w-[250px]">
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        v-model="formModel.polda_name"
                        placeholder="Polda"
                        @keyup="poldaSearch()"
                        @focusin="popoverPolda = true"
                    />
                    <float-label>Pilih Polda</float-label>
                    <div
                        class="absolute mt-2 z-20 inline-block drop-shadow-lg left-0 w-[30vh] text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm "
                        :class="popoverPolda ? '' : 'hidden'"
                    >
                        <div
                            class="px-3 relative py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg ">
                            <button @click="popoverPolda = false" type="button"
                                    class="absolute top-1 right-2.5 text-gray-400 bg-transparent hover:bg-red-300 hover:text-red-900 rounded-lg text-sm px-2.5 py-2 ml-auto inline-flex items-center"
                            >
                                <font-awesome-icon icon="fa-solid fa-close"/>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <h3 class="font-semibold text-gray-900">Pilih Polda</h3>
                        </div>
                        <div class="px-3 py-2 h-[468px] overflow-y-scroll">
                            <div>
                                <button type="button"
                                        class="cursor-pointer text-gray-600 flex items-center rounded w-full my-2 px-3 py-2 font-bold hover:bg-blue-200"
                                        :class="formModel.polda_id === '%%' ? 'bg-blue-400' : 'bg-gray-200'"
                                        @click="choosePolda({id: '%%', name: 'Semua'})"
                                >
                                    SEMUA
                                </button>
                            </div>
                            <div v-for="polda in arrPolda">
                                <button type="button"
                                        class="cursor-pointer text-gray-600 flex items-center rounded w-full my-2 px-3 py-2 font-bold hover:bg-blue-200"
                                        :class="formModel.polda_id === polda.id ? 'bg-blue-400' : 'bg-gray-200'"
                                        @click="choosePolda(polda)"
                                >
                                    {{ polda.name }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <Select v-model="formModel.bulan" class="text-green-500" :data="arrBulan" @change="changeInput"/>
                    <float-label>Pilih Bulan</float-label>
                </div>
                <div class="w-32 relative">
                    <text-input class="w-full" model-value="{{formModel.tahun}}" v-model="formModel.tahun" type="number"
                                placeholder="Tahun" @change="changeInput"/>
                    <float-label>Pilih Tahun</float-label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">

            <div v-if="isLoadingPerpus" role="status"
                 class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap animate-pulse">
                        <div class="relative pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-300 rounded bg-gray-300 w-3/4 h-3"></h5>
                            <p class="text-gray-300 rounded mt-2 bg-gray-300 text-xl w-32 h-4"></p></div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-gray-300">
                                <font-awesome-icon :icon="['fas','school']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm bg-gray-300 rounded w-52 text-gray-300 mt-2 animate-pulse h-3">
                    </p>
                </div>
            </div>

            <div v-if="!isLoadingPerpus" class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-400 uppercase font-bold text-xs">Perpustakaan Terapung</h5><span
                            class="font-semibold text-xl text-gray-700">{{ formModel.jml_perpus }} Orang</span></div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-blue-500">
                                <font-awesome-icon :icon="['fas','school']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                        <span class="whitespace-nowrap">Jumlah Peserta {{
                                formModel.bulan === '%%' ? 'Tahun ' + formModel.tahun : 'Bulan ' + arrBulan.find(x => x.id === formModel.bulan).name
                            }}</span>
                    </p></div>
            </div>

            <div v-if="isLoadingKlinik" role="status"
                 class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap animate-pulse">
                        <div class="relative pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-300 rounded bg-gray-300 w-3/4 h-3"></h5>
                            <p class="text-gray-300 rounded mt-2 bg-gray-300 text-xl w-32 h-4"></p></div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-gray-300">
                                <font-awesome-icon :icon="['fas','house-medical-flag']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm bg-gray-300 rounded w-52 text-gray-300 mt-2 animate-pulse h-3">
                    </p>
                </div>
            </div>

            <div v-if="!isLoadingKlinik" class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-400 uppercase font-bold text-xs">Klinik Terapung</h5><span
                            class="font-semibold text-xl text-gray-700">{{ formModel.jml_klinik }} Pasien</span></div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-pink-500">
                                <font-awesome-icon :icon="['fas','house-medical-flag']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                        <span class="whitespace-nowrap">Jumlah Pasien {{
                                formModel.bulan === '%%' ? 'Tahun ' + formModel.tahun : 'Bulan ' + arrBulan.find(x => x.id === formModel.bulan).name
                            }}</span>
                    </p></div>
            </div>

            <div v-if="isLoadingSampah" role="status"
                 class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap animate-pulse">
                        <div class="relative pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-300 rounded bg-gray-300 w-3/4 h-3"></h5>
                            <p class="text-gray-300 rounded mt-2 bg-gray-300 text-xl w-32 h-4"></p></div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-gray-300">
                                <font-awesome-icon :icon="['fas','recycle']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm bg-gray-300 rounded w-52 text-gray-300 mt-2 animate-pulse h-3">
                    </p>
                </div>
            </div>

            <div v-if="!isLoadingSampah" class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-400 uppercase font-bold text-xs">Sampah Organik</h5>
                            <span class="font-semibold text-xl text-gray-700 group ">{{
                                    convertKgToTon(formModel.jml_organik)
                                }}
                            <span v-if="formModel.jml_organik >= 1000"
                                class="bg-gray-100 border border-gray-400 rounded px-2 py-1 text-base text-gray-500 drop-shadow-xl hidden group-hover:block whitespace-nowrap absolute z-10">
                                {{ parseInt(formModel.jml_organik).toFixed(1) }} Kg
                            </span>
                            </span>
                        </div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-green-500">
                                <font-awesome-icon :icon="['fas','recycle']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                        <span class="whitespace-nowrap">Jumlah Sampah {{
                                formModel.bulan === '%%' ? 'Tahun ' + formModel.tahun : 'Bulan ' + arrBulan.find(x => x.id === formModel.bulan).name
                            }}</span>
                    </p></div>
            </div>

            <div v-if="isLoadingSampah" role="status"
                 class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap animate-pulse">
                        <div class="relative pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-300 rounded bg-gray-300 w-3/4 h-3"></h5>
                            <p class="text-gray-300 rounded mt-2 bg-gray-300 text-xl w-32 h-4"></p></div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-gray-300">
                                <font-awesome-icon :icon="['fas','trash-can']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm bg-gray-300 rounded w-52 text-gray-300 mt-2 animate-pulse h-3">
                    </p>
                </div>

            </div>

            <div v-if="!isLoadingSampah" class="relative flex flex-col min-w-0 break-words bg-white rounded shadow-lg">
                <div class="flex-auto p-2">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1"><h5
                            class="text-gray-400 uppercase font-bold text-xs">Sampah Anorganik</h5>
                            <span
                                class="font-semibold text-xl text-gray-700 group">{{
                                    convertKgToTon(formModel.jml_anorganik)
                                }}
                                <span v-if="formModel.jml_anorganik >= 1000"
                                      class="bg-gray-100 border border-gray-400 rounded px-2 py-1 text-base text-gray-500 drop-shadow-xl hidden group-hover:block whitespace-nowrap absolute z-10">
                                {{ parseInt(formModel.jml_anorganik).toFixed(1) }} Kg
                            </span>
                            </span>
                        </div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-10 h-10 shadow-lg rounded-full bg-red-500">
                                <font-awesome-icon :icon="['fas','trash-can']"
                                                   class="w-4 h-4 rounded-full object-contain text-white"/>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                        <span class="whitespace-nowrap">Jumlah Sampah {{
                                formModel.bulan === '%%' ? 'Tahun ' + formModel.tahun : 'Bulan ' + arrBulan.find(x => x.id === formModel.bulan).name
                            }}</span>
                    </p></div>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
