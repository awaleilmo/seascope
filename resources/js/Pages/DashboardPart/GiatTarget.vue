<script setup>

import {onBeforeMount, onMounted, ref, watchEffect} from "vue";
import {initFlowbite} from "flowbite";
import GraphicModel from "@/Models/Graphic.model.js";
import decodeConfig from "@/Configuration/decode.config.js";
import encodeConfig from "@/Configuration/encode.config.js";
import TextInput from "@/Components/TextInput.vue";
import TargetModel from "@/Models/Target.model.js";
import Select from "@/Components/Select.vue";
import DecodeConfig from "@/Configuration/decode.config.js";
import PoldaModel from "@/Models/Polda.model.js";
import EncodeConfig from "@/Configuration/encode.config.js";
import FloatLabel from "@/Components/FloatLabel.vue";

// Variable
const Model = GraphicModel
const formModel = ref({
    id : null,
    tanggal : '',
    tahun : '',
    bulan : '%%',
    lokasi_id : '%%',
    lokasi_name : 'Semua Lokasi',
    polda_id : '%%',
    polda_name : 'Semua Polda',
    jml_perpus : '',
    jml_klinik : '',
    jml_organik : '',
    jml_anorganik : '',
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
const series = ref([
    {
        name: 'Aktual',
        data: []
    }
])
const noData = ref(false)
const chartOptions = ref({
    chart: {
        animations: {
            enabled: true,
            easing: 'linear',
            speed: 800,
            animateGradually: {
                enabled: true,
                delay: 150
            },
            dynamicAnimation: {
                enabled: true,
                speed: 350
            }
        }
    },
    plotOptions: {
        bar: {
            horizontal: true,
            columnWidth: '50%',
            barHeight: '50%',
        }
    },
    colors: ['#00E396'],
    dataLabels: {
        formatter: function (val, opt) {
            const goals =
                opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex]
                    .goals
            const hitung = ((val / goals[0].value) * 100).toFixed(1)
            if (goals && goals.length) {
                return `${hitung}%`
            }
            return val
        }
    },
    fill: {
        type: "gradient",
        gradient: {
            gradientToColors: ["#6df555", "#6078ea", "#6094ea"]
        }
    },
    legend: {
        show: true,
        showForSingleSeries: true,
        customLegendItems: ['Aktual', 'Target'],
        markers: {
            fillColors: ['#00E396', '#775DD0']
        }
    },
    yaxis: {
        labels: {
            show: true,
            style: {
                colors: [],
                fontSize: '14px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        },
    }
})
const arrPolda = ref([])
const arrBkPolda = ref([])
const popoverPolda = ref(false);
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
const updateData = async () => {
    let encode = encodeConfig.base64(JSON.stringify(formModel.value))
    let dataRawTarget = await SubModel.getByTahun(encode)
    let dataRaw = await Model.giat(encode);
    let data = decodeConfig.base64toJSON(dataRaw.data.content)
    let bagi = 1
    if (formModel.value.bulan !== '%%') {
        bagi = 12
    }
    if (data.length <= 0 || dataRawTarget.data.content === null) {
        noData.value = true
        isLoading.value = false
        return
    }
    noData.value = false
    let dataTarget = decodeConfig.base64toJSON(dataRawTarget.data.content)
    series.value[0].data = [
        {
            x: 'Sambang Nusa',
            y: data[0]['sambang'],
            goals: [
                {
                    name: 'Target',
                    value: dataTarget['sambang'] / bagi,
                    strokeHeight: 20,
                    strokeWidth: 4,
                    strokeColor: '#775DD0'
                }
            ]
        },
        {
            x: 'Klinik',
            y: data[0]['klinik'],
            goals: [
                {
                    name: 'Target',
                    value: dataTarget['klinik'] / bagi,
                    strokeHeight: 20,
                    strokeWidth: 4,
                    strokeColor: '#775DD0'
                }
            ]
        },
        {
            x: 'Perpustakaan',
            y: data[0]['perpustakaan'],
            goals: [
                {
                    name: 'Target',
                    value: dataTarget['perpustakaan'] / bagi,
                    strokeHeight: 20,
                    strokeWidth: 4,
                    strokeColor: '#775DD0'
                }
            ]
        },
        {
            x: 'Sapu Bersih Sampah',
            y: data[0]['sampah'],
            goals: [
                {
                    name: 'Target',
                    value: dataTarget['sapu'] / bagi,
                    strokeHeight: 20,
                    strokeWidth: 4,
                    strokeColor: '#775DD0'
                }
            ]
        },
        {
            x: 'Polisi RW',
            y: data[0]['rw'],
            goals: [
                {
                    name: 'Target',
                    value: dataTarget['rw'] / bagi,
                    strokeHeight: 20,
                    strokeWidth: 4,
                    strokeColor: '#775DD0'
                }
            ]
        },
    ]
}
const changeInput = async () => {
    isLoading.value = true
    await updateData()
    isLoading.value = false
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
         class="min-h-[565px] h-fit border border-gray-100 bg-white  rounded-lg p-4  md:p-6">
        <div class="flex flex-col animate-pulse">
            <div>
                <h5 class="h-7 bg-gray-200 rounded mb-2.5"></h5>
                <p class="w-48 h-3 bg-gray-200 rounded"></p>
            </div>
            <div
                class="flex justify-between mt-4 items-center py-2 gap-1 text-base font-semibold text-green-500 text-center">
                <div class="h-10 bg-gray-200 rounded w-1/2"></div>
                <div class="h-10 bg-gray-200 rounded w-1/6"></div>
                <div class="h-10 bg-gray-200 rounded w-1/3"></div>
            </div>
        </div>
        <div class="grid grid-cols-4 items-baseline mt-5 gap-2 animate-pulse">
            <div class="flex flex-col items-end mt-5 animate-pulse">
                <div class="w-3/4 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-1/2 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-2/3 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-3/5 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-2/6 mb-2 bg-gray-200 rounded-lg h-10"></div>
            </div>
            <div class="flex col-span-3 flex-col items-baseline animate-pulse mt-5">
                <div class="w-3/4 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-1/2 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-2/3 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-3/5 mb-2 bg-gray-200 rounded-lg h-10"></div>
                <div class="w-2/6 mb-2 bg-gray-200 rounded-lg h-10"></div>
            </div>
        </div>
    </div>

    <div v-if="!isLoading" class="w-full shadow hover:shadow-xl transition-all h-fit bg-white rounded-lg p-4 md:p-6">

        <div class="flex flex-col">
            <div>
                <h5 class="leading-none text-2xl font-bold text-gray-900 pb-2">Statistic Target Information Display {{ formModel.polda_id === '%%' ? '' : formModel.polda_name }}</h5>
                <p class="text-base font-normal text-gray-500">Tahun {{ formModel.tahun }}</p>
            </div>
            <div
                class="flex justify-between items-center mt-2 w-full py-2 gap-1 text-base font-semibold text-green-500 text-center">
                <div class="relative w-1/2">
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

        <div id="chart">
            <apexchart v-if="!noData" type="bar" height="350" :options="chartOptions" :series="series"/>
            <h1 v-if="noData"
                class="text-5xl h-[350px] flex items-center justify-center text-gray-400 font-bold text-center">Tidak
                Ada Data</h1>
        </div>

        <div class="grid grid-cols-1 items-center border-gray-200 border-t justify-between">
            <div class="flex justify-between items-center pt-5">
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
