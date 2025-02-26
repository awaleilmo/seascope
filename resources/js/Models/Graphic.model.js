import graphicService from "@/Services/Graphic.service.js";

class GraphicModel {
    constructor() {
        this.id = null;
        this.tanggal = '';
        this.tahun = '';
        this.bulan  = '%%';
        this.lokasi_id = '%%';
        this.lokasi_name = 'Semua Lokasi'
        this.polda_id = '%%';
        this.polda_name = 'Semua Polda';
        this.jml_perpus = '';
        this.jml_klinik = '';
        this.jml_organik = '';
        this.jml_anorganik = '';
    }

    giat(data) {
        return graphicService.serviceGiat(data);
    }
    sampah(data) {
        return graphicService.serviceSampah(data);
    }
    klinik(data) {
        return graphicService.serviceKlinik(data);
    }
    perpustakaan(data) {
        return graphicService.servicePerpustakaan(data);
    }

    programPolda(data) {
        return graphicService.serviceProgramPolda(data);
    }
}

export default new GraphicModel()
