import L from "leaflet";
class initMap {
    index = () => {
        let defaultMap = L.tileLayer(
            'https://tile.openstreetmap.de/{z}/{x}/{y}.png',
            {
                subdomains: ['a', 'b', 'c'],
            }
        )
        let satelliteMap = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }
        )
        let baseLayers = {
            'Default': defaultMap,
            'Satellite': satelliteMap
        }
        let bMap = L.map('map', {
            layers: [satelliteMap],
            zoomControl: false
        })
        let layerControl = L.control.layers(baseLayers)
        layerControl.setPosition('topleft')
        layerControl.addTo(bMap)

        let zoomControl = L.control.zoom()
        zoomControl.setPosition('bottomright')
        zoomControl.addTo(bMap)

        return {
            map: bMap, lyControl: layerControl, leaflet: L
        }
    }
}

export default new initMap()
