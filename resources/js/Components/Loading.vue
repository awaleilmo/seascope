<script setup>
const props = defineProps({
    loading: Boolean
})
</script>
<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="loading"
                 class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 items-center justify-center flex">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="loading" class="fixed backdrop-blur-lg inset-0 transform transition-all" >
                        <div class="absolute inset-0 bg-gray-300 opacity-75"/>
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
                        v-show="loading"
                        class="mb-6 rounded-full bg-amber-50 shadow-2xl   overflow-hidden transform transition-all"
                    >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-[70px] h-[70px] sm:w-[80px] sm:h-[80px]"
                                 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                <circle cx="75" cy="50" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.9166666666666666s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.9166666666666666s"></animate>
                                </circle>
                                <circle cx="71.65063509461098" cy="62.5" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.8333333333333334s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.8333333333333334s"></animate>
                                </circle>
                                <circle cx="62.5" cy="71.65063509461096" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.75s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.75s"></animate>
                                </circle>
                                <circle cx="50" cy="75" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.6666666666666666s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.6666666666666666s"></animate>
                                </circle>
                                <circle cx="37.50000000000001" cy="71.65063509461098" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.5833333333333334s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.5833333333333334s"></animate>
                                </circle>
                                <circle cx="28.34936490538903" cy="62.5" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.5s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.5s"></animate>
                                </circle>
                                <circle cx="25" cy="50" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.4166666666666667s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.4166666666666667s"></animate>
                                </circle>
                                <circle cx="28.34936490538903" cy="37.50000000000001" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.3333333333333333s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.3333333333333333s"></animate>
                                </circle>
                                <circle cx="37.499999999999986" cy="28.34936490538904" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.25s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.25s"></animate>
                                </circle>
                                <circle cx="49.99999999999999" cy="25" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.16666666666666666s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.16666666666666666s"></animate>
                                </circle>
                                <circle cx="62.5" cy="28.349364905389034" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="-0.08333333333333333s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="-0.08333333333333333s"></animate>
                                </circle>
                                <circle cx="71.65063509461096" cy="37.499999999999986" fill="#dcfff8" r="5">
                                    <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s"
                                             repeatCount="indefinite" begin="0s"></animate>
                                    <animate attributeName="fill" values="#dcfff8;#dcfff8;#00e2ff;#dcfff8;#dcfff8"
                                             repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s"
                                             begin="0s"></animate>
                                </circle>
                            </svg>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>

<style scoped>

</style>
