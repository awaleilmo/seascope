import mapService from "@/Services/Map.service.js";

class MapService {
    constructor() {
        this.baserun = '';
        this.dtime = '';
        this.depth = '';
    }
    inflow(baserun, dtime, depth){
        return mapService.serviceInflow(baserun, dtime, depth)
    }
    inaWaves(baserun, dtime){
        return mapService.serviceInaWaves(baserun, dtime)
    }
    gempa(){
        return mapService.serviceGempa()
    }
}

export default new MapService();
