<script setup>
import GraphicModel from "@/Models/Graphic.model.js";
import {onMounted, ref} from "vue";
import TargetModel from "@/Models/Target.model.js";
import PoldaModel from "@/Models/Polda.model.js";
import Select from "@/Components/Select.vue";
import TextInput from "@/Components/TextInput.vue";
import FloatLabel from "@/Components/FloatLabel.vue";
import DecodeConfig from "@/Configuration/decode.config.js";
import EncodeConfig from "@/Configuration/encode.config.js";

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
const SubModel = TargetModel
const ModelPolda = PoldaModel
const isLoading = ref(true)
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
const booting = ref(true)
const noData = ref(false)
const arrPolda = ref([])
const arrBkPolda = ref([])
const arrData = ref([])
const popoverPolda = ref(false);
const columns = ref([
    {label: 'NO', field: 'no', sortable: false, width: '50px'},
    {label: 'DITPOLAIR POLDA', field: 'lokasi.alias', sortable: true, sort: 1, width: 'auto', customClass: 'uppercase'},
    {label: 'SAMBANG NUSA PRESISI', field: 'sambang_count', sortable: true, sort: 0, width: 'auto'},
    {label: 'POLISI RW', field: 'rw_count', sortable: true, sort: 0, width: 'auto'},
    {label: 'KAPAL PERPUSTAKAAN TERAPUNG', field: 'perpustakaan_count', sortable: true, sort: 0, width: 'auto'},
    {label: 'KAPAL KLINIK TERAPUNG', field: 'klinik_count', sortable: true, sort: 0, width: 'auto'},
    {label: 'BERSIH SAMPAH LAUT', field: 'sapu_count', sortable: true, sort: 0, width: 'auto'},
])

// Method
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
const updateData = async () => {
    let encode = EncodeConfig.base64(JSON.stringify(formModel.value))
    let dataRaw = await Model.programPolda(encode)
    let data = DecodeConfig.base64toJSON(dataRaw.data.content)
    if (data.length <= 0 || dataRaw.data.content === null) {
        noData.value = true
        isLoading.value = false
        return
    }
    noData.value = false
    arrData.value = data
}
const changeInput = async () => {
    isLoading.value = true
    await updateData()
    isLoading.value = false
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
const choosePolda = async (val) => {
    isLoading.value = true
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
const columnParsing = (header, row) => {
    let split = header.field.split('.')
    if (split.length > 1) {
        return row[split[0]][split[1]]
    }
    return row[header.field]
}
const changeSortColumn = (val, index) => {
    let sort = val.sort === 0 ? 2 : (val.sort === 2 ? 1 : 2)
    let split = val.field.split('.')
    columns.value.map((item, i) => {
        if (i === index) {
            item.sort = sort
        } else {
            item.sort = 0
        }
        return item
    })
    if (arrData.value.length > 0) {
        arrData.value.sort((x, y) => {
            let  a = x[val.field], b = y[val.field]
            if (split.length > 1) {
                a = x[split[0]].alias.toUpperCase()
                b = y[split[0]].alias.toUpperCase()
            }

            if (val.sort === 1) {
                return a === b ? 0 : a > b ? 1 : -1;
            }

            if (val.sort === 2) {
                return a === b ? 0 : a < b ? 1 : -1;
            }
            return 0
        })
    }
}

// Lifecycle
onMounted(() => {
    startUp()
})
</script>

<template>
    <div v-if="isLoading" role="status"
         class="min-h-fit h-fit border border-gray-100 bg-white  rounded-lg p-4  md:p-6">
        <div class="flex flex-col animate-pulse">
            <div>
                <h5 class="h-7 bg-gray-200 rounded mb-2.5"></h5>
                <p class="w-48 h-3 bg-gray-200 rounded"></p>
            </div>
            <div
                class="flex mt-4 items-center py-2 gap-1 text-base font-semibold text-green-500 text-center">
                <div class="h-10 bg-gray-200 rounded w-1/3"></div>
                <div class="h-10 bg-gray-200 rounded w-1/6"></div>
                <div class="h-10 bg-gray-200 rounded w-1/4"></div>
            </div>
        </div>
        <div class=" mt-5 animate-pulse">
            <div class="w-full mb-2 bg-gray-200 rounded h-52"></div>
        </div>
    </div>

    <div v-if="!isLoading" class="w-full shadow hover:shadow-xl transition-all h-fit bg-white rounded-lg p-4 md:p-6">
        <div class="flex flex-col">
            <div>
                <h5 class="leading-none text-2xl font-bold text-gray-900 pb-2">REKAPITULASI PELAKSANAAN PROGRAM UNGGULAN
                    JAJARAN DITPOLAIR POLDA {{ formModel.polda_id === '%%' ? '' : formModel.polda_name }}</h5>
                <p class="text-base font-normal text-gray-500">Tahun {{ formModel.tahun }}</p>
            </div>
            <div
                class="flex items-center mt-2 w-full py-2 gap-1 text-base font-semibold text-green-500 text-center">
                <div class="relative w-1/3">
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
                <div class="relative w-auto">
                    <Select v-model="formModel.bulan" class="text-green-500" :data="arrBulan" @change="changeInput"/>
                    <float-label>Pilih Bulan</float-label>
                </div>
                <div class="w-auto relative">
                    <text-input class="w-full" model-value="{{formModel.tahun}}" v-model="formModel.tahun" type="number"
                                placeholder="Tahun" @change="changeInput"/>
                    <float-label>Pilih Tahun</float-label>
                </div>
            </div>
        </div>

        <div class="relative py-2 px-2 overflow-x-auto sm:rounded-lg shadow-md hover:shadow-xl hover:shadow-green-200 ">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th v-for="(header, index) in columns"
                        :key="index"
                        scope="col"
                        :style="{
                            width: header.width
                        }"
                        class="px-6 py-3 border">
                        <div class="flex items-center text-center">
                            {{ header.label }}
                            <font-awesome-icon
                                @click="changeSortColumn(header, index)"
                                :icon="['fas', header.sort === 1 ? 'fa-sort-up' : (header.sort === 2 ? 'fa-sort-down' : 'fa-sort')]"
                                class="text-gray-500 text-xs w-3 h-3 ms-1.5 cursor-pointer" v-if="header.sortable"/>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="noData" class="bg-white border-b h-[150px]">
                    <td class="px-6 py-4 text-center" :colspan="columns.length">
                        No Data
                    </td>
                </tr>
                <tr v-else v-for="(row, index) in arrData" class="bg-white border-b">
                    <td class="px-6 py-2 border" v-for="header in columns"
                        :class="header.customClass ? header.customClass : ''"
                    >
                        {{ header.field === 'no' ? index + 1 : columnParsing(header, row) }}
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td class="px-6 py-2 border font-bold text-lg text-black" :colspan="2">
                        Total
                    </td>
                    <td class="px-6 py-2 border font-bold text-base text-black">
                        {{ arrData.reduce((prev, curr) => prev + curr['sambang_count'], 0) }}
                    </td>
                    <td class="px-6 py-2 border font-bold text-base text-black">
                        {{ arrData.reduce((prev, curr) => prev + curr['rw_count'], 0) }}
                    </td>
                    <td class="px-6 py-2 border font-bold text-base text-black">
                        {{ arrData.reduce((prev, curr) => prev + curr['perpustakaan_count'], 0) }}
                    </td>
                    <td class="px-6 py-2 border font-bold text-base text-black">
                        {{ arrData.reduce((prev, curr) => prev + curr['klinik_count'], 0) }}
                    </td>
                    <td class="px-6 py-2 border font-bold text-base text-black">
                        {{ arrData.reduce((prev, curr) => prev + curr['sapu_count'], 0) }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

    </div>
</template>

<style scoped>

</style>
