import perpustakaanService from "@/Services/Perpustakaan.service.js";

class PerpustakaanModel {
    constructor() {
        this.id = null;
        this.polda_name = '';
        this.polda_id = null;
        this.personil_jml = [];
        this.personil_sat = [];
        this.lokasi = '';
        this.jml_peserta = '';
        this.asal_peserta = '';
        this.hasil = '';
        this.keterangan = '';
        this.foto = null;
        this.foto_original = null;
        this.tanggal = '';
        this.waktu = '';
    }
    createUpdate(data) {
        return perpustakaanService.serviceCreateUpdate(data);
    }
    getAll() {
        return perpustakaanService.serviceGetAll();
    }
    getById(id) {
        return perpustakaanService.serviceGetById(id);
    }
    getPage(param) {
        return perpustakaanService.servicePage(param);
    }
    destroy(id) {
        return perpustakaanService.serviceDestroy(id);
    }
    getFoto(id) {
        return perpustakaanService.serviceGetFoto(id);
    }
    sapuDownload(params) {
        return perpustakaanService.serviceDownload(params);
    }
    postPhoto(data) {
        return perpustakaanService.servicePostPhoto(data);
    }
    deletePhoto(id, urutan) {
        return perpustakaanService.serviceDeletePhoto(id, urutan);
    }

}

export default new PerpustakaanModel();
