import userService from "@/Services/User.service.js";

class UserModel {
    constructor() {
        this.id = null;
        this.name = '';
        this.username = '';
        this.email = '';
        this.password = null;
        this.password_confirmation = null;
        this.role_id = null;
        this.role_name = '';
        this.polda_id = null;
        this.polda_name = '';
    }

    createUpdate(data) {
        return userService.serviceCreateUpdate(data);
    }
    getAll() {
        return userService.serviceGetAll();
    }
    getById(id) {
        return userService.serviceGetById(id);
    }
    getPage(param) {
        return userService.servicePage(param);
    }
    destroy(id) {
        return userService.serviceDestroy(id);
    }
}

export default new UserModel()
