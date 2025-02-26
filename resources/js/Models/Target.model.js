import targetService from "@/Services/Target.service.js";

class TargetModel {
    constructor() {
        this.id = null;
        this.klinik = '';
        this.perpustakaan = '';
        this.rw = '';
        this.sambang = '';
        this.sapu = '';
        this.tahun = '';
    }
    createUpdate(data) {
        return targetService.serviceCreateUpdate(data);
    }
    getByTahun(id) {
        return targetService.serviceGetByTahun(id);
    }
    getPage(param) {
        return targetService.servicePage(param);
    }
    destroy(id) {
        return targetService.serviceDestroy(id);
    }
}

export default new TargetModel();
