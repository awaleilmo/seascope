<script setup>

import {computed, onBeforeMount, onMounted, onUnmounted, onUpdated, ref, watch} from 'vue';
import Modal from "@/Components/Modal.vue";
import {LMap} from "@vue-leaflet/vue-leaflet";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    }
});

const markers = ref([])
const zoom = ref(16)
const emit = defineEmits(['close']);
const realPosition = ref({
    lat: -6.2236190,
    lon: 106.7988280
})
const locationAlert = ref({
    status: false,
    message: "",
})

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    localStorage.removeItem('lat')
    localStorage.removeItem('lon')
    if (props.closeable) {
        emit('close');
    }
};
const saveLocation = () => {
    if (props.closeable) {
        emit('close');
    }
}
const getLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            start,
            showError
        );
    } else {
        locationAlert.value.status = false;
        locationAlert.value.message =
            "Geolokasi tidak didukung oleh browser ini.";
    }
}
const start = (position) => {
    locationAlert.value.status = false;
    realPosition.value.lat = position.coords.latitude;
    realPosition.value.lon = position.coords.longitude;

}
const showError = (error) => {
    locationAlert.value.status = true;
    switch (error.code) {
        case error.PERMISSION_DENIED:
            locationAlert.value.message =
                "Pengguna menolak permintaan Geolokasi.";
            break;
        case error.POSITION_UNAVAILABLE:
            locationAlert.value.message =
                "Informasi lokasi tidak tersedia.";
            break;
        case error.TIMEOUT:
            locationAlert.value.message =
                "The request to get user location timed out.";
            break;
    }
}
const logging = (a) => {
    markers.value = [
        a.latlng
    ]
    localStorage.setItem('lat', a.latlng.lat)
    localStorage.setItem('lon', a.latlng.lng)
}
const mapReady = map => {
    map.on('click', logging)
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onUpdated(() => {
    if (props.show) {
        getLocation()
        setTimeout(function () {
            window.dispatchEvent(new Event('resize'))
        }, 250);
    }
})
onMounted(() => {
     markers.value = localStorage.getItem('lat') !== null ? [
        {
            lat: localStorage.getItem('lat'),
            lng: localStorage.getItem('lon')
        }
    ] : []
    document.addEventListener('keydown', closeOnEscape)
});

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
            <div v-show="show" class="fixed inset-0 overflow-y-auto z-50 items-center justify-center">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 transform transition-all">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"/>
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
                        class="bg-white transition-all sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <div v-show="show" class="h-screen p-0 w-full">
                            <l-map
                                v-model="zoom"
                                v-model:zoom="zoom"
                                :center="[realPosition.lat, realPosition.lon]"
                                :options="{zoomControl: false}"
                                @ready="mapReady"
                                style="z-index: 0; height: 100vh; width: 100vw; position: relative"
                            >
                                <!--                                <l-tile-layer-->
                                <!--                                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"-->
                                <!--                                    layer-type="base"-->
                                <!--                                    name="OpenStreetMap"-->
                                <!--                                    attribution='&copy; <a href="https://integrapadma.id/">Integra Padma Mandiri</a> '-->
                                <!--                                />-->
                                <l-tile-layer
                                    url="https://{s}.google.com/vt/lyrs=m@221097413&x={x}&y={y}&z={z}"
                                    layer-type="base"
                                    :subdomains="['mt0', 'mt1', 'mt2', 'mt3']"
                                    name="OpenStreetMap"
                                    attribution='&copy; <a href="https://integrapadma.id/">Integra Padma Mandiri</a> '
                                />
                                <l-control position="topright">
                                    <button type="button" @click="close"
                                            class="lg:top-3 lg:right-8 text-gray-900 border-2 border-gray-900/50 bg-gray-100 hover:bg-gray-400/50 hover:text-gray-900 rounded-lg text-xl px-3 py-2 ml-auto inline-flex items-center right-1"
                                    >
                                        <font-awesome-icon icon="fa-solid fa-close"/>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </l-control>
                                <l-control position="bottomleft" style="margin-bottom: 0; margin-left: 0">
                                    <div class="w-screen flex justify-center bg-gray-100 drop-shadow-lg shadow-lg pt-6">
                                        <primary-button class="w-[90vw] sm:w-[50vw] mb-5 " @click="saveLocation">
                                            <span>SAVE</span>
                                        </primary-button>
                                    </div>
                                </l-control>
                                <l-control-zoom position="topright"></l-control-zoom>
                                <l-control-scale position="topleft" :imperial="false" :metric="true"/>
                                <l-marker :lat-lng="[realPosition.lat, realPosition.lon]">
                                    <l-icon
                                        :icon-size="[40, 40]"
                                        :icon-anchor="[20, 50]"
                                        icon-url="https://cdn-icons-png.flaticon.com/512/1673/1673221.png">
                                    </l-icon>
                                </l-marker>
                                <l-marker v-for="(marker) in markers" :lat-lng="marker">
                                    <l-icon
                                        :icon-size="[40, 40]"
                                        :icon-anchor="[20, 50]"
                                        icon-url="https://cdn-icons-png.flaticon.com/512/2098/2098567.png">
                                    </l-icon>
                                </l-marker>
                            </l-map>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
    <Modal :show="locationAlert.status">
        <div class="relative">
            <div class="relative bg-white rounded-lg shadow">
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50">
                    <h3 class="text-base font-semibold lg:text-xl">
                        {{ locationAlert.message }}
                    </h3>
                </div>
            </div>
        </div>
    </Modal>
</template>
