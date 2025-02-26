<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {onBeforeMount, ref} from "vue";
import PoldaModel from "@/Models/Polda.model.js";
import DecodeConfig from "@/Configuration/decode.config.js";
import sysConfig from "@/Configuration/sys.config.js";

const props = defineProps(
    {
        title: {
            type: String,
            default: 'Polda'
        },
        poldaName: {
            type: String,
            require: true
        },
        poldaId: {
            type: Number,
            require: true
        },
        placeholder: {
            type: String,
            default: 'Polda'
        },
        allPolda: {
            type: Boolean,
            default: false
        },
        choosePolda: {
            type: Boolean,
        }
    }
)

const ModelPolda = PoldaModel;
const popoverPolda = ref(false);
const rendering = ref(false);

const arrPolda = ref([])
const arrBkPolda = ref([])

const emit = defineEmits(['choosePolda']);

const getPolda = async () => {
    let rawPolda = await ModelPolda.getAll()
    let auth = sysConfig.userAuth()
    let decode = DecodeConfig.base64toJSON(rawPolda.data.content)
    arrPolda.value = decode.filter((item) => {
        if (auth.polda_id === 0) {
            return {
                id: item.id,
                name: item.name,
            }
        } else {
            if (auth.polda_id === item.id) {
                return {
                    id: item.id,
                    name: item.name,
                }
            }
        }
    })
    arrBkPolda.value = arrPolda.value
    rendering.value = true
}
const ChoosePolda = (val) => {
    emit('choosePolda', val)
    popoverPolda.value = false
}
const poldaSearch = () => {
    if (arrPolda.value.length >= 0) {
        arrPolda.value = arrBkPolda.value.filter(item => item['name'].toLowerCase().indexOf(props.poldaName.toLowerCase()) > -1)
    }
}

onBeforeMount(() => {
    getPolda()
})

</script>

<template>
    <InputLabel :value="props.title"/>
    <TextInput
        type="text"
        class="mt-1 block w-full"
        v-model="props.poldaName"
        :placeholder="props.placeholder"
        @keyup="poldaSearch()"
        @focusin="popoverPolda = true"
    />
    <div
        v-if="rendering"
        class="absolute z-10 inline-block drop-shadow-lg w-[70vh] text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm "
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
        <div class="px-3 py-2 h-[400px] overflow-y-scroll">
            <button v-if="props.allPolda" type="button"
                    class="cursor-pointer text-gray-600 flex items-center rounded w-full my-2 px-3 py-2 font-bold hover:bg-blue-200"
                    :class="props.poldaId === 0 ? 'bg-blue-400 text-white' : 'bg-gray-300'"
                    @click="ChoosePolda({id: 0, name: 'SEMUA POLDA'})"
            >
                SEMUA POLDA
            </button>
            <div v-for="polda in arrPolda">
                <button type="button"
                        class="cursor-pointer text-gray-600 flex items-center rounded w-full my-2 px-3 py-2 font-bold hover:bg-blue-200"
                        :class="props.poldaId === polda.id ? 'bg-blue-400 text-white' : 'bg-gray-200'"
                        @click="ChoosePolda(polda)"
                >
                    {{ polda.name }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
