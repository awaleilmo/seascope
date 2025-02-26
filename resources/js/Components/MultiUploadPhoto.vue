<script setup>
import {computed, onMounted, onUnmounted, ref, watch} from 'vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import encodeConfig from "@/Configuration/encode.config.js";
import sysConfig from "@/Configuration/sys.config.js";
import Notification from "@/Components/Notification.vue";
import decodeConfig from "@/Configuration/decode.config.js";
import EncodeConfig from "@/Configuration/encode.config.js";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '90vw',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    urlSave: {
        type: String,
        default: 'postPhoto',
    },
    urlGet: {
        type: String,
        default: 'getFoto',
    },
    urlDelete: {
        type: String,
        default: 'deletePhoto',
    },
    model: {
        default: null,
    },
});

const emit = defineEmits(['close']);
const inLoading = ref(false)
const fullscreen = ref(false)
const arrModel = ref([]);
const photo = ref(null);
const photoPreview = ref(null);
const alerts = ref({
    status: false,
    message: '',
    type: 'info',
})

watch(
    () => props.show,
    () => {
        if (props.show) {
            inLoading.value = true
            document.body.style.overflow = 'hidden';
            startUp()
        } else {
            document.body.style.overflow = null;
            arrModel.value = []
            inLoading.value = false
            photo.value = null
            photoPreview.value = null
        }
    }
);
const startUp = async () => {
    let id = encodeConfig.base64(props.model['id'])
    let raw = await props.model.getFoto(id)
    const decodeFoto = decodeConfig.base64toJSON(raw.data.content)
    decodeFoto.forEach((item, i) => {
        arrModel.value.push({
            id: props.model['id'],
            photo: decodeConfig.base64(item.foto),
            file: decodeConfig.base64(item.foto),
            active: i === 0,
            loading: false,
            processing: false,
            error: false,
            success: false,
            new: false,
            change: false,
            urutan: item.urutan,
            message: ''
        })
        if (i === 0) {
            photoPreview.value = decodeConfig.base64(item.foto)
        }
    })
    inLoading.value = false
}
const close = () => {
    if (props.closeable) {
        emit('close');
    }
};
const addArr = () => {
    let i = arrModel.value.length
    let data = arrModel.value.sort((a, b) => a.urutan - b.urutan)[i - 1]
    let urutan = 1
    if (data !== undefined) {
        urutan = data.urutan + 1
    }
    arrModel.value.push({
        id: props.model['id'],
        photo: null,
        file: null,
        active: false,
        loading: false,
        processing: false,
        error: false,
        success: false,
        new: true,
        change: true,
        urutan: urutan,
        message: ''
    })
    setTimeout(() => {
        openDom(i + 1)
    }, 200);
};
const openDom = (i) => {
    let id = 'photo' + i
    let dom = document.getElementById(id)
    dom.click()
};
const changePhoto = async (val, index) => {
    const type = val.target.files[0].type
    const file = val.target.files[0];
    const before = arrModel.value[index].photo
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
            arrModel.value[index].photo = await sysConfig.ImageBase64Compress(canvas.toDataURL(type), type, 0.8);
            arrModel.value[index].file = encodeConfig.base64(await sysConfig.ImageBase64Compress(canvas.toDataURL(type), type, 0.8));
            let active = arrModel.value[index].active
            arrModel.value[index].change = true
            if (active) {
                photoPreview.value = arrModel.value[index].photo
            }
            if (index === 0 && before === null) {
                activePreview()
            }
        };
    }
    reader.readAsDataURL(file);
}
const cancelChange = (index) => {
    if (arrModel.value[index].photo === null) {
        arrModel.value.splice(index, 1)
    }
}
const previewOpen = (index) => {
    arrModel.value.map((i, dex) => {
        if (dex === index) {
            i.active = !i.active
        } else {
            i.active = false
        }
    })
    photoPreview.value = arrModel.value[index].photo
}
const validatePhoto = async () => {
    inLoading.value = true
    let modalHeader = document.getElementById('modal-header')
    if (arrModel.value.length <= 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Pilih minimal 1 foto'
        alerts.value.type = 'danger'
        inLoading.value = false
        return false
    }
    arrModel.value.map((i) => {
        i.loading = i['change'] === true
        i.error = false
        i.success = false
        i.processing = false
        i.message = ''
    })
    let check = arrModel.value.filter((i) => i['change'] === true).length
    if (check > 0) {
        await savePhoto()
    } else {
        inLoading.value = false
        close()
    }
    return true
}
const savePhoto = async () => {
    setTimeout(async () => {
        let url = props.urlSave
        let dataFilter = arrModel.value.filter((i) => i['change'] === true)
        dataFilter.map(async (i, index) => {
            i.processing = true
            let save = await props.model[url](i)
            if (save.data.status) {
                i.success = true
                i.new = false
                i.change = false
                i.error = false
                i.processing = false
                i.message = ''
            }
            if (!save.data.status) {
                i.error = true
                i.success = false
                i.processing = false
                i.message = save.data.message
            }
        })
        setTimeout(() => {
            let filterEnd = dataFilter.filter((i) => i['success'] === true)
            if (filterEnd.length === dataFilter.length) {
                arrModel.value.map((i) => {
                    i.loading = false
                    i.success = false
                    i.new = false
                    i.change = false
                    i.error = false
                    i.processing = false
                })
                inLoading.value = false
            }
        }, 2000)
        inLoading.value = false
    }, 1000)
}
const activePreview = () => {
    let lng = arrModel.value.length
    if (lng > 0) {
        arrModel.value[0].active = true
        photoPreview.value = arrModel.value[0].photo
    } else {
        photoPreview.value = null
    }
}
const removeArr = async (index) => {
    let data = arrModel.value[index]
    if (data.new) {
        arrModel.value.splice(index, 1)
        activePreview(data)
    }
    if (!data.new) {
        inLoading.value = true
        data.loading = true
        data.processing = true
        let encodeId = EncodeConfig.base64(JSON.stringify(data.id))
        let encodeUrutan = EncodeConfig.base64(JSON.stringify(data.urutan))
        let del = await props.model[props.urlDelete](encodeId, encodeUrutan)
        if (del.data.status) {
            data.success = true
            data.error = false
            data.processing = false
            setTimeout(() => {
                inLoading.value = false
                data.loading = false
                arrModel.value.splice(index, 1)
                activePreview()
            }, 1000)
        }
        if (!del.data.status) {
            data.error = true
            data.success = false
            data.processing = false
            data.loading = false
            setTimeout(() => {
                inLoading.value = false
            }, 1000)
        }
    }
}
onUnmounted(() => {
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
        'full': 'sm:max-w-full',
        '10vw': 'sm:max-w-[10vw]',
        '20vw': 'sm:max-w-[20vw]',
        '30vw': 'sm:max-w-[30vw]',
        '40vw': 'sm:max-w-[40vw]',
        '50vw': 'sm:max-w-[50vw]',
        '60vw': 'sm:max-w-[60vw]',
        '70vw': 'sm:max-w-[70vw]',
        '80vw': 'sm:max-w-[80vw]',
        '90vw': 'sm:max-w-[90vw]',
        '100vw': 'sm:max-w-[100vw]',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-2 sm:px-0 z-50 items-center justify-center">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 transform transition-all">
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
                        class="my-4 bg-white rounded-lg shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <div v-if="show">
                            <!-- Modal content -->
                            <div class=" bg-white rounded-lg shadow">
                                <button :disabled="inLoading" @click="close" type="button"
                                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-red-300 hover:text-red-900 rounded-lg text-sm px-2.5 py-2 ml-auto inline-flex items-center"
                                >
                                    <font-awesome-icon icon="fa-solid fa-close"/>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <!-- Modal header -->
                                <div id="modal-header" class="px-6 py-4 border-b rounded-t bg-gray-100">
                                    <h3 class="text-base font-semibold text-gray-900 lg:text-xl ">
                                        Upload Foto
                                    </h3>
                                </div>

                                <Notification :alerts="alerts"/>

                                <!-- Modal body -->
                                <div class="p-6 flex flex-col gap-2 items-center">
                                    <div
                                        :class="fullscreen ? 'fixed top-0 left-0 w-full h-fit ' : 'relative sm:w-[50em] h-[450px]'"
                                        class="bg-gray-200 rounded group flex items-center justify-center border hover:shadow-lg hover:shadow-green-200">
                                        <img v-if="photoPreview !== null" :src="photoPreview" alt="preview"
                                             class="h-full object-contain rounded w-full"/>
                                        <font-awesome-icon v-else icon="fa-solid fa-image"
                                                           class="w-8 h-8 text-gray-400"/>
                                        <div v-if="photoPreview !== null" class="absolute top-2 right-2 bg-blue-300/70 rounded p-1 hidden group-hover:flex cursor-pointer" @click="fullscreen = !fullscreen">
                                            <font-awesome-icon :icon="'fa-solid ' + (fullscreen ? 'fa-compress' : 'fa-expand')"
                                                               class="w-7 h-7 text-gray-200"/>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex gap-2 w-full flex-wrap justify-center">
                                        <div v-for="(i, index) in arrModel" :key="index">
                                            <div v-if="i.loading"
                                                 class="w-36 h-28 group backdrop-blur-sm absolute rounded flex items-center justify-center">
                                                <font-awesome-icon
                                                    v-if="i.loading && !i.processing && !i.success && !i.error"
                                                    icon="fa-solid fa-clock"
                                                    class="h-8 w-8 text-gray-200 drop-shadow-2xl"/>
                                                <font-awesome-icon v-if="i.processing" icon="fa-solid fa-spinner"
                                                                   class="h-8 w-8 text-blue-500 drop-shadow-2xl animate-spin "/>
                                                <font-awesome-icon v-if="i.success" icon="fa-solid fa-circle-check"
                                                                   class="h-8 w-8 text-green-400 brightness-125 drop-shadow-lg"/>
                                                <font-awesome-icon v-if="i.error" icon="fa-solid fa-circle-exclamation"
                                                                   class="h-8 w-8 text-red-500 brightness-125 drop-shadow-lg"/>
                                                <p v-if="i.error"
                                                   class="group-hover:flex text-xs hidden absolute break-all overflow-auto text-ellipsis max-h-64 line-clamp-2 rounded p-2 w-36 bg-gray-300 text-red-400">
                                                    {{ i.message }}
                                                </p>
                                            </div>
                                            <div v-if="i.photo !== null"
                                                 :class="i.active ? 'ring ring-blue-400' : ''"
                                                 class="rounded transition-all ease-in-out duration-200 hover:ring hover:ring-blue-300 group shadow-lg shadow-amber-300/50 flex items-center justify-center">
                                                <div
                                                    v-if="!i.loading"
                                                    @click="previewOpen(index)"
                                                    class="group-hover:transition-all group-hover:delay-150 group-hover:duration-700 gap-2 group-hover:ease-out group-hover:opacity-100 hidden group-hover:block w-36 h-28 bg-gray-500/50 absolute rounded"/>
                                                <div
                                                    v-if="!i.loading"
                                                    class="absolute hidden group-hover:flex justify-evenly items-center w-20 bg-gray-100 rounded h-fit py-2">
                                                    <font-awesome-icon icon="fa-solid fa-pencil"
                                                                       class="h-4 w-4 text-amber-500 cursor-pointer"
                                                                       @click="openDom(index+1)"/>
                                                    <font-awesome-icon icon="fa-solid fa-trash"
                                                                       class="h-4 w-4 text-red-500 cursor-pointer"
                                                                       @click="removeArr(index)"/>
                                                </div>
                                                <img :src="i.photo" alt="preview"
                                                     class="w-36 h-28 object-contain rounded"/>
                                            </div>
                                            <font-awesome-icon v-else icon="fa-solid fa-image" @click="openDom(index+1)"
                                                               class="w-28 h-28 cursor-pointer text-gray-400"/>
                                            <input :id="'photo' + (index+1)" type="file" class="hidden"
                                                   accept="image/*" @change="changePhoto($event, index)"
                                                   @cancel="cancelChange(index)"/>
                                        </div>
                                        <secondary-button :processing="inLoading" @click="addArr"
                                                          class="min-h-28 min-w-28 flex items-center justify-center hover:shadow-xl hover:shadow-green-200">
                                            <font-awesome-icon class="w-6 h-6" icon=" fa-solid fa-plus"/>
                                        </secondary-button>
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="px-4 py-2 flex justify-end border-t gap-2 rounded-b bg-gray-100 ">
                                    <SecondaryButton :processing="inLoading" @click="close">Close</SecondaryButton>
                                    <primary-button :processing="inLoading" @click="validatePhoto()">
                                        Save
                                    </primary-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
