import poldaService from "@/Services/Polda.service.js";

class PoldaModel {
    constructor() {
        this.id = null;
        this.name = '';
        this.kota = '';
        this.alamat = '';
        this.lokasi_id = null;
    }

    createUpdate(data) {
        return poldaService.serviceCreateUpdate(data);
    }
    getAll() {
        return poldaService.serviceGetAll();
    }
    getById(id) {
        return poldaService.serviceGetById(id);
    }
    getPage(param) {
        return poldaService.servicePage(param);
    }
    destroy(id) {
        return poldaService.serviceDestroy(id);
    }
}

export default new PoldaModel()
