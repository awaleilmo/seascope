<script setup>
import {computed, onBeforeMount, onMounted, ref, watchEffect} from 'vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import {initFlowbite} from "flowbite";
import sysConfig from "@/Configuration/sys.config.js";
import {router} from "@inertiajs/vue3";
import Loading from "@/Components/Loading.vue";

// Variable
const props = defineProps({
    propsLoading: {
        type: Boolean,
        default: false
    }
})

// Variable
const isLoading = ref(props.propsLoading);
const clickLoading = ref(false);
const userAuth = ref(sysConfig.userAuth());

// Methods
const href = (value) => {
    clickLoading.value = true;
    isLoading.value = true;
    setTimeout(() => {
        localStorage.setItem('activePage', '1')
        localStorage.setItem('page', '1')
        localStorage.setItem('perPage', '10')
        localStorage.setItem('search', '')
        localStorage.setItem('column', 'name')
        window.location.href = window.location.origin + value
    }, 200)
}

const menus = ref([])

watchEffect(() => {
    if (clickLoading.value === false && props.propsLoading !== isLoading.value) {
        isLoading.value = props.propsLoading
    }
});

// Lifecycle
onBeforeMount(async () => {
    isLoading.value = true;
    let role = parseInt(userAuth.value['role_id'].toString());
    menus.value = [
        {
            name: 'Dashboard',
            href: '/dashboard',
            route: 'dashboard',
            icon: 'chart-pie',
            dropdown: false,
            toggle: false,
            dropBody: [],
            active: [1, 2, 3, 4].includes(role)
        },
        // {
        //     name: 'Sambang Nusa Presisi',
        //     href: '/sambang',
        //     route: 'sambang',
        //     icon: 'house-flood-water-circle-arrow-right',
        //     dropdown: false,
        //     toggle: false,
        //     dropBody: [],
        //     active: [1, 2, 3].includes(role)
        // },
        // {
        //     name: 'Sapu Bersih Sampah',
        //     href: '/sapu',
        //     route: 'sapu',
        //     icon: 'broom',
        //     dropdown: false,
        //     toggle: false,
        //     dropBody: [],
        //     active: [1, 2, 3].includes(role)
        // },
        // {
        //     name: 'Perpustakaan Terapung',
        //     href: '/perpustakaan',
        //     route: 'perpustakaan',
        //     icon: 'school',
        //     dropdown: false,
        //     toggle: false,
        //     dropBody: [],
        //     active: [1, 2, 3].includes(role)
        // },
        // {
        //     name: 'Klinik Terapung',
        //     href: '/klinik',
        //     route: 'klinik',
        //     icon: 'house-medical-flag',
        //     dropdown: false,
        //     toggle: false,
        //     dropBody: [],
        //     active: [1, 2, 3].includes(role)
        // },
        // {
        //     name: 'Polisi RW',
        //     href: '/polisiRw',
        //     route: 'polisiRw',
        //     icon: 'building-shield',
        //     dropdown: false,
        //     toggle: false,
        //     dropBody: [],
        //     active: [1, 2, 3].includes(role)
        // },
        // {
        //     name: 'Patroli Air',
        //     href: '/polisiRw',
        //     route: 'patroli',
        //     icon: 'bridge-water',
        //     dropdown: true,
        //     toggle: router.page.url.split('/').at(-2) === 'patroli',
        //     dropBody: [
        //         {
        //             name: 'Gakkum',
        //             href: '/patroli/gakkum',
        //             route: 'gakkum',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2, 3].includes(role)
        //         },
        //         {
        //             name: 'Kia',
        //             href: '/patroli/kia',
        //             route: 'kia',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2, 3].includes(role)
        //         },
        //         {
        //             name: 'Global',
        //             href: '/patroli/global',
        //             route: 'polisi-global',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2, 3].includes(role)
        //         }
        //     ],
        //     active: [1, 2, 3].includes(role)
        // },
        // {
        //     name: 'Setting',
        //     href: '#',
        //     route: 'setting',
        //     icon: 'gear',
        //     dropdown: true,
        //     toggle: router.page.url.split('/').at(-2) === 'setting',
        //     dropBody: [
        //         {
        //             name: 'Target Program Unggulan',
        //             href: '/setting/target',
        //             route: 'setting-target',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2].includes(role)
        //         },
        //         {
        //             name: 'Polda',
        //             href: '/setting/polda',
        //             route: 'setting-polda',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2].includes(role)
        //         },
        //         {
        //             name: 'Lokasi',
        //             href: '/setting/lokasi',
        //             route: 'setting-lokasi',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2].includes(role)
        //         },
        //         {
        //             name: 'Role',
        //             href: '/setting/role',
        //             route: 'setting-role',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2].includes(role)
        //         },
        //         {
        //             name: 'User',
        //             href: '/setting/user',
        //             route: 'setting-user',
        //             dropdown: false,
        //             toggle: false,
        //             dropBody: [],
        //             active: [1, 2].includes(role)
        //         },
        //     ],
        //     active: [1, 2].includes(role)
        // }
    ]
})

onMounted(() => {
    isLoading.value = false;
    initFlowbite();
})
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="relative top-0 z-50 w-full border-b bg-gray-50 border-gray-100">
                <div class="px-3 py-3 lg:px-5 lg:pl-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-start">
                            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                                    aria-controls="logo-sidebar" type="button"
                                    class="inline-flex items-center p-2 text-sm rounded-lg sm:hidden focus:outline-none focus:ring-2 text-gray-400 hover:bg-gray-700 focus:ring-gray-600">
                                <span class="sr-only">Open sidebar</span>
                                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                          d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                                </svg>
                            </button>
                            <a href="/" class="sm:flex absolute hidden ml-2 md:mr-24">
                                <img src="@/Assets/logo-seascope.png" class="h-14 mr-3" alt="FlowBite Logo"/>
                            </a>
                        </div>
                        <header v-if="$slots.header">
                            <h2 class="font-semibold text-xl text-gray-500 leading-tight">
                                <slot name="header"/>
                            </h2>
                        </header>
                        <div class="flex items-center">
                            <div class="flex items-center ml-3">
                                <div>
                                    <button type="button"
                                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-600"
                                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                        <span class="sr-only">Open user menu</span>
                                        <font-awesome-icon :icon="['fas', 'circle-user']"
                                                           class="w-8 h-8 rounded-full object-contain text-white"/>
                                    </button>
                                </div>
                                <div
                                    class="z-50 hidden my-4 text-base list-none divide-y rounded shadow bg-gray-50 divide-gray-400"
                                    id="dropdown-user">
                                    <div class="px-4 py-3" role="none">
                                        <p class="text-sm text-gray-600" role="none">
                                            {{ userAuth.name }}
                                        </p>
                                        <p class="text-sm font-medium truncate text-gray-300" role="none">
                                            {{ userAuth.email }}
                                        </p>
                                    </div>
                                    <ul class="py-1" role="none">
                                        <li>
                                            <DropdownLink href="/logout" method="post" as="button"
                                                          class="text-gray-600">Sign out
                                            </DropdownLink>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- sidebar -->
            <aside id="logo-sidebar"
                   class="fixed hidden top-0 left-0 z-40 w-[63px] group h-screen hover:w-[250px] hover:transition-all pt-20 transition-transform -translate-x-full border-r sm:translate-x-0 bg-gray-50 sm:shadow-2xl"
                   aria-label="Sidebar">
                <div class="h-full px-3 pb-4 overflow-y-hidden">
                    <ul class="space-y-2 font-medium">
                        <li>
                            <a href="/" class="flex sm:hidden p-2 md:mr-24">
                                <img src="@/Assets/logo.png" class="h-8 mr-3" alt="FlowBite Logo"/>
                            </a>
                        </li>
                        <li v-for="menu in menus" :key="menu.route">
                            <div v-if="menu.active"
                                 @click="menu.dropdown ? menu.toggle = !menu.toggle : href(menu.href)"
                                 class="flex cursor-pointer items-center p-2 rounded-lg h-9 text-white hover:bg-gray-500/50 group"
                                 :class="{ 'bg-blue-700': route().current(menu.route) }"
                            >
                                <font-awesome-icon :icon="['fas', menu.icon]"
                                                   class="w-5 h-5 transition duration-75 group-hover:text-white"
                                                   :class="route().current(menu.route) ? 'text-white' : 'text-gray-400'"
                                />
                                <span
                                    class="flex-1 hidden group-hover:block group-hover:transition-all ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white group-hover:font-bold"
                                    :class="route().current(menu.route) ? 'text-white' : 'text-gray-400'">{{
                                        menu.name
                                    }}</span>
                                <font-awesome-icon v-if="menu.dropdown"
                                                   :icon="['fas', menu.toggle ? 'caret-up' : 'caret-down']"
                                                   class="w-4 h-4 font-bold transition duration-75 group-hover:text-white"
                                                   :class="route().current(menu.route) ? 'text-white' : 'text-gray-400'"
                                />
                            </div>
                            <ul v-if="menu.dropdown && menu.active" v-show="menu.toggle" class="py-2 space-y-2">
                                <li v-for="dropBody in menu.dropBody" :key="dropBody.route">
                                    <div v-if="dropBody.active" @click="href(dropBody.href)"
                                         class="flex cursor-pointer items-center p-2 ml-5 rounded-lg text-white hover:bg-gray-500/50 group"
                                         :class="{ 'bg-blue-700': route().current(dropBody.route) }"
                                    >
                                        <font-awesome-icon :icon="['fas', 'circle']"
                                                           class="w-1.5 h-1.5 transition duration-75 group-hover:text-white"
                                                           :class="route().current(dropBody.route) ? 'text-white' : 'text-gray-400'"
                                        />
                                        <span
                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white group-hover:font-bold"
                                            :class="route().current(dropBody.route) ? 'text-white' : 'text-gray-400'">{{
                                                dropBody.name
                                            }}</span>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Page Content -->
            <main>
                <div class="p-0 sm:ml-[0px] mt-0">
                    <slot/>
                </div>
            </main>

            <loading :loading="isLoading"/>
        </div>
    </div>
</template>
