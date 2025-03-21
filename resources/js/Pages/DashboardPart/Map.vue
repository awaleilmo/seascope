<script setup>
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import "leaflet-velocity/dist/leaflet-velocity.js";
import "leaflet-velocity/dist/leaflet-velocity.css";
import "leaflet.fullscreen/Control.FullScreen.css";
import "leaflet.fullscreen/Control.FullScreen.js";
import {onBeforeMount, onMounted, ref} from "vue";
import teritory from "@/Configuration/teritory.js";
import zee from "@/Configuration/Zee.js";
import mapModel from "@/Models/Map.model.js";
import boatNormal from "@/Assets/kapal 2.png"
import quakeGiff from "@/Assets/quake.gif";
import aisService from "@/Services/ais.service.js";
import {
    LMap,
    LTileLayer,
    LMarker,
    LIcon,
    LGeoJson,
    LPopup,
} from "@vue-leaflet/vue-leaflet";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

// Variable
const isLoading = ref(true);
const model = ref([]);
const url = new URL(location.href).pathname;
const buttonToggleLayer = ref(false);
const center = ref(L.latLng(-2.6683467, 116.5489434));
const bounds = ref(null);
const myMap = ref(null);
const tileProviders = ref([
    {
        name: "Default",
        url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        subdomains: ["a", "b", "c"],
        visible: true,
    },
    {
        name: "Satellite",
        url: "https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}",
        subdomains: ["mt0", "mt1", "mt2", "mt3"],
        visible: false,
    },
]);
const splitUrl = url.split("/");
const zoom = ref(6);
const realPosition = ref({
    lat: "-3.5519883",
    lon: "118.4492136",
});
const teritorial = ref(teritory);
const ZEE = ref(zee);
const arusLaut = (bMap, layerControl, datas) => {
    let vel = L.velocityLayer({
        displayValues: true,
        displayOptions: {
            velocityType: "Arus Laut",
            displayPosition: "bottomleft",
            displayEmptyString: "No water data",
        },
        data: datas,
        maxVelocity: 1.5,
        velocityScale: 0.2,
    });
    layerControl.addOverlay(vel, "Arus Laut");
    vel.addTo(bMap);
}
// const bmkgSignificantWave = ref(`https://peta-maritim.bmkg.go.id/api21/mpl_req/w3g_hires/swh/0/${currDateYYYYMMDD}0000/${currDateYYYYMMDD}1200/{z}/{x}/{y}.png?ci=1&overlays=,contourf,contour,value&conc=snow`)
// const bmkgSignificantWaveArrow = ref(`https://peta-maritim.bmkg.go.id/api21/arr_req/w3g_hires/dir/0/${currDateYYYYMMDD}0000/${currDateYYYYMMDD}1200/{z}/{x}/{y}.png?color=white`)
// Method
const initMap = async (bMap, layerControl) => {
    let date = new Date();

    let zoomControl = L.control.zoom();
    zoomControl.setPosition("bottomright");
    zoomControl.addTo(bMap);

    let ctrl = L.control();
    ctrl.onAdd = function () {
        let controlHome =
            '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#fff" d="M543.8 287.6c17 0 32-14 32-32.1c1-9-3-17-11-24L512 185V64c0-17.7-14.3-32-32-32H448c-17.7 0-32 14.3-32 32v36.7L309.5 7c-6-5-14-7-21-7s-15 1-22 8L10 231.5c-7 7-10 15-10 24c0 18 14 32.1 32 32.1h32v69.7c-.1 .9-.1 1.8-.1 2.8V472c0 22.1 17.9 40 40 40h16c1.2 0 2.4-.1 3.6-.2c1.5 .1 3 .2 4.5 .2H160h24c22.1 0 40-17.9 40-40V448 384c0-17.7 14.3-32 32-32h64c17.7 0 32 14.3 32 32v64 24c0 22.1 17.9 40 40 40h24 32.5c1.4 0 2.8 0 4.2-.1c1.1 .1 2.2 .1 3.3 .1h16c22.1 0 40-17.9 40-40V455.8c.3-2.6 .5-5.3 .5-8.1l-.7-160.2h32z"/></svg>';
        let btnIn = L.DomUtil.create(
            "button",
            "cursor-pointer border border-gray-100 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium px-4 py-2.5 rounded"
        );
        btnIn.setAttribute("title", "Reset Map");
        btnIn.innerHTML = controlHome;
        L.DomEvent.on(btnIn, "click", L.DomEvent.stop).on(
            btnIn,
            "click",
            function () {
                bMap.setView(center.value, 5);
            }
        );
        return btnIn;
    };
    ctrl.addTo(bMap);

    let iconEarthQuake = new L.Icon({
        iconUrl: quakeGiff,
        iconSize: L.point(35, 35),
        iconAnchor: [19, 30],
    });

    let geoJsonTeritory = L.geoJSON(teritorial.value, {
        style: {
            weight: 3,
        },
    });
    layerControl.addOverlay(geoJsonTeritory, "Teritorial");

    let geoJsonZee = L.geoJSON(ZEE.value, {
        style: {
            weight: 3,
            color: "red",
        },
    });
    layerControl.addOverlay(geoJsonZee, "ZEE");
    geoJsonZee.addTo(bMap);

    isLoading.value = false

    let groupLayerGempa = L.layerGroup();
    await mapModel.gempa().then((item) => {
        const data = item.data.content;
        for (let i = 0; i < data.length; i++) {
            let latLong = data[i]["Coordinates"].split(",");
            let gempa = L.marker(latLong, {icon: iconEarthQuake}).addTo(
                groupLayerGempa
            );
            gempa.bindTooltip(
                L.tooltip({
                    offset: L.point(0, -10),
                }).setContent(`
                        <p class="capitalize text-base px-4 py-2 text-gray-600">
                            Tanggal: ${data[i]["Tanggal"]} </br>
                            Jam: ${data[i]["Jam"]} </br>
                            Lintang: ${data[i]["Lintang"]} </br>
                            Bujur: ${data[i]["Bujur"]} </br>
                            Magnitudo: <span class="font-bold text-lg">${data[i]["Magnitude"]}</span> </br>
                            Kedalaman: <span class="font-bold text-lg">${data[i]["Kedalaman"]}</span> </br>
                            Wilayah: ${data[i]["Wilayah"]} </br>
                            Potensi: ${data[i]["Potensi"]} </br>
                        </p>`)
            );
        }
    });
    layerControl.addOverlay(groupLayerGempa, "Gempa");
    groupLayerGempa.addTo(bMap);

    // let currDateYYYYMMDD = date.getFullYear() + '' + (date.getMonth() < 10 ? '0' + (date.getMonth() + 1) : 1) + '' + (date.getDate() < 10 ? '0' + date.getDate() : date.getDate())
    //
    // let groupLayerBmkg = L.layerGroup();
    // let bmkgSignificantWave = L.tileLayer(`https://peta-maritim.bmkg.go.id/api21/mpl_req/w3g_hires/swh/0/${currDateYYYYMMDD}0000/${currDateYYYYMMDD}1200/{z}/{x}/{y}.png?ci=1&overlays=,contourf,contour,value&conc=snow`, {
    //     maxZoom: 10,
    //     subdomains: ['a', 'b', 'c'],
    //     attribution: 'BMKG Significant Wave',
    //     tms: true,
    //     opacity: 0.8
    // })
    // let bmkgSignificantWaveArrow = L.tileLayer(`https://peta-maritim.bmkg.go.id/api21/arr_req/w3g_hires/dir/0/${currDateYYYYMMDD}0000/${currDateYYYYMMDD}1200/{z}/{x}/{y}.png?color=white`, {
    //     maxZoom: 10,
    //     subdomains: ['a', 'b', 'c'],
    //     attribution: 'BMKG Significant Wave',
    //     tms: true,
    //     opacity: 0.8
    // })
    // bmkgSignificantWave.addTo(groupLayerBmkg)
    // bmkgSignificantWaveArrow.addTo(groupLayerBmkg)
    // layerControl.addOverlay(groupLayerBmkg, 'BMKG Significant Wave')

    let baserun =
        date.getFullYear() +
        "" +
        (date.getMonth() < 10 ? "0" + (date.getMonth() + 1) : 1) +
        "" +
        (date.getDate() < 10 ? "0" + date.getDate() : date.getDate()) +
        "0000";
    let dtime =
        date.getFullYear() +
        "" +
        (date.getMonth() < 10 ? "0" + (date.getMonth() + 1) : 1) +
        "" +
        (date.getDate() < 10 ? "0" + date.getDate() : date.getDate()) +
        "0000";
    await mapModel.inflow(baserun, dtime, 0).then(async (data) => {
        if (data.data.status) {
            arusLaut(bMap, layerControl, data.data.content);
        } else {
            await mapModel.inaWaves(baserun, dtime).then((data) => {
                arusLaut(bMap, layerControl, data.data.content);
            })
        }
    });
};

const aisData = async (bMap, layerControl) => {
    let aisLayer = L.layerGroup().addTo(bMap);
    layerControl.addOverlay(aisLayer, "AIS Data");
    let timeOut = true;
    const fetchDataAis = async () => {
        try {
            let response = await aisService.serviceGet();
            aisLayer.clearLayers();
            if (response.success) {
                let data = response.data
                data.forEach((ais) => {
                    let shipRotation = ais.course !== null ? ais.course : (ais.heading !== null && ais.heading <= 360 ? ais.heading : 0);
                    let iconHTML = `<div style="width: 10px; height: 30px; transform: rotate(${shipRotation}deg);">
                                        <img src="${boatNormal}" style="width: 100%; height: 100%;" alt="boat" />
                                    </div>`;

                    let iconMarker = L.divIcon({
                        className: "", // Hapus class yang bisa mengganggu
                        html: iconHTML,
                        iconSize: [20, 40], // Ukuran eksplisit
                        iconAnchor: [10, 20] // Pastikan titik anchor sesuai
                    });


                    if (ais.lat !== null && ais.lon !== null) {
                        let marker = L.marker([ais.lat, ais.lon], {
                            icon: iconMarker,
                            rotationAngle: shipRotation,
                            rotationOrigin: "center"
                        }).addTo(aisLayer);
                        marker.bindTooltip(
                            `<p><strong>MMSI:</strong> ${ais.mmsi}<br>
                <strong>Speed:</strong> ${ais.speed} knots<br>
                <strong>Course:</strong> ${ais.course}°<br>
                <strong>Status:</strong> ${ais.status}</p>`
                        );
                    }
                });
                timeOut = true
            }
        } catch (error) {
            timeOut = true
            console.error("Error fetching AIS data:", error);
        }
    }
    if(timeOut){
        timeOut = false
        setInterval(fetchDataAis, 5000);
    }
    await fetchDataAis();
}
const startUp = async () => {
    let defaultMap = L.tileLayer(
        "https://tile.openstreetmap.de/{z}/{x}/{y}.png",
        {
            subdomains: ["a", "b", "c"],
        }
    );
    let satelliteMap = L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        {
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    );
    let bMap = L.map("map", {
        layers: [satelliteMap],
        zoomControl: false,
        center: center.value,
        zoom: zoom.value,
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: "bottomright",
        },
    });
    let baseLayers = {
        Default: defaultMap,
        Satellite: satelliteMap,
    };

    let layerControl = L.control.layers(baseLayers);
    layerControl.setPosition("topleft");
    layerControl.addTo(bMap);

    await aisData(bMap, layerControl);
    await initMap(bMap, layerControl);
};

// Lifecycle
onMounted(() => {
    startUp();
});
</script>

<template>
    <div
        class="relative text-base bg-white px-0 py-0 shadow hover:shadow-xl rounded-lg w-full text-gray-500 min-h-full"
    >
        <div
            v-if="isLoading"
            class="absolute flex items-center justify-center top-0 left-0 h-full bg-gray-100 mr-10 w-full rounded-lg"
            style="z-index: 999"
        >
            <font-awesome-icon
                :icon="['fas', 'spinner']"
                class="w-20 h-20 rounded-full object-contain text-gray-300 animate-spin"
            />
        </div>
        <!--        <LMap ref="myMap" :zoom="zoom" :center="center">-->
        <!--            <LTileLayer-->
        <!--                url="https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"-->
        <!--                :subdomains="['a', 'b', 'c']"-->
        <!--            />-->
        <!--            <LMarker :lat-lng="center"/>-->
        <!--            <LGeoJson :geojson="teritorial"/>-->
        <!--            <LGeoJson :geojson="ZEE"/>-->
        <!--        </LMap>-->
        <div ref="myMap" id="map" class="h-full w-full z-10"/>
    </div>
</template>

<style scoped></style>
