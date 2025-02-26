import lokasiService from "@/Services/Lokasi.service.js";

class LokasiModel {
    constructor() {
        this.id = null;
        this.name = '';
        this.alias = '';
    }

    createUpdate(data) {
        return lokasiService.serviceCreateUpdate(data);
    }

    getAll() {
        return lokasiService.serviceGetAll();
    }

    getById(id) {
        return lokasiService.serviceGetById(id);
    }

    getPage(param) {
        return lokasiService.servicePage(param);
    }

    destroy(id) {
        return lokasiService.serviceDestroy(id);
    }
}

export default new LokasiModel();
