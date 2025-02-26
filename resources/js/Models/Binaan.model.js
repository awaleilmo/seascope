import sambangService from "@/Services/Sambang.service.js";

class BinaanModel {
    getPage(param) {
        return sambangService.serviceBinaanPage(param);
    }
}

export default new BinaanModel();
