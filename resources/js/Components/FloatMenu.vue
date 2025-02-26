<script setup>
import {ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {Link} from '@inertiajs/vue3';
import male_pic from "@/Assets/male_pic.png";
import female_pic from "@/Assets/female_pic.png";
import NavLink from "@/Components/NavLink.vue";

// data
const active = ref(false);
const imagePreview = ref({
    status: false,
    image: null,
})
const close = () => {
    active.value = false;
};

const imagePreviewFn = (e) => {
    imagePreview.value.status = true
    imagePreview.value.image = e.target.src
}

const isSex = (value) => {
    if (value.foto === null) {
        if (value.jenisKelamin === 'L') {
            return male_pic
        } else {
            return female_pic
        }
    } else {
        return value.typeFoto + ',' + value.foto
    }
}

</script>

<template>
    <div class="fixed bottom-4 z-10 right-2 md:bottom-10 md:right-10 transition-all group">
        <transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-show="active" class="fixed inset-0 transform transition-all" @click="close">
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
                v-show="active"
                class=" rounded-lg overflow-hidden transform transition-all sm:w-full sm:mx-auto"
            >
                <div v-if="active"
                     class="flex flex-col justify-end shadow-lg py-1 mb-4 space-y-2 bg-white border border-gray-100 rounded-lg">
                    <ul class="text-sm text-gray-500">
                        <li>
                            <a href="/dashboard"
                               class="flex items-center px-5 py-2 hover:bg-gray-100 hover:text-gray-900">
                                <font-awesome-icon class="w-5 h-5 mr-2" :icon="['fas', 'house']"/>
                                <span class="text-sm font-medium">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="/daftarKaryawan"
                               class="flex items-center px-5 py-2 hover:bg-gray-100 hover:text-gray-900">
                                <font-awesome-icon class="w-5 h-5 mr-2" :icon="['fas', 'users']"/>
                                <span class="text-sm font-medium">Daftar Karyawan</span>
                            </a>
                        </li>
                        <li>
                            <a href="/riwayatKehadiran"
                               class="flex items-center px-5 py-2 hover:bg-gray-100 hover:text-gray-900 ">
                                <font-awesome-icon class="w-5 h-5 mr-2" :icon="['fas', 'list']"/>
                                <span class="text-sm font-medium">Riwayat Kehadiran</span>
                            </a>
                        </li>
                        <li  v-if="$page.props.auth.user.role !== 99 && $page.props.auth.user.role !== 1" class="sm:hidden">
                            <a href="/sakit"
                               class="flex items-center px-5 py-2 hover:bg-gray-100 hover:text-gray-900 ">
                                <font-awesome-icon class="w-5 h-5 mr-2" :icon="['fas', 'kit-medical']"/>
                                <span class="text-sm font-medium">Form Sakit</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pt-4 sm:hidden pb-1 border-t border-gray-200">
                        <div class="px-4 flex justify-center items-center flex-col">
                            <img
                                 :src="isSex($page.props.auth.user)"
                                 @click="imagePreviewFn"
                                 class="w-16 h-16 rounded-full object-cover" alt="..."/>
                            <div class="font-medium text-base text-gray-400 capitalize">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1 flex items-center justify-around">
                            <primary-button>
                                <a :href="route('profile.edit')"> Profile</a>
                            </primary-button>
                            <danger-button>
                                <Link :href="route('logout')" method="post">
                                    Logout
                                </Link>
                            </danger-button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <primary-button
            class="flex items-center transform justify-center ml-auto text-white rounded-full w-11 h-11 md:w-16 md:h-16 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 focus:outline-none"
            @click="active = !active">
            <transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 scale-0 rotate-45"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="ease-out duration-100"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-0 rotate-45"
            >
                <font-awesome-icon v-if="!active" size="lg" :icon="['fas', 'bars']"/>
                <font-awesome-icon v-else size="2x" :icon="['fas', 'xmark']"/>
            </transition>
            <span class="sr-only">Open actions menu</span>
        </primary-button>
    </div>

    <Modal :show="imagePreview.status" @close="imagePreview.status = false">
        <img :src="imagePreview.image" alt="Bonnie image" class="w-full"/>
    </Modal>
</template>

<style scoped>

</style>
