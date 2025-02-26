import roleService from "@/Services/Role.service.js";

class RoleModel {
    constructor() {
        this.id = null;
        this.name = '';
        this.sub = 0
    }

    createUpdate(data) {
        return roleService.serviceCreateUpdate(data);
    }
    getAll() {
        return roleService.serviceGetAll();
    }
    getById(id) {
        return roleService.serviceGetById(id);
    }
    getPage(param) {
        return roleService.servicePage(param);
    }
    destroy(id) {
        return roleService.serviceDestroy(id);
    }
}

export default new RoleModel()
