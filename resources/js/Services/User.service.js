import axios from 'axios';

let API_URL = '/api/user/';

class UserService {
    service(method, url, data) {
        return axios({
            url: url,
            method: method,
            data: data
        })
            .then(response => {
                return { success: true, data: response.data };
            })
            .catch(error => {
                return { success: false, data: error.response.data.message };
            });
    }

    serviceGetAll() {
        return this.service('get', API_URL+'all');
    }
    serviceGetById(id) {
        return this.service('get', API_URL+'find/'+id);
    }
    serviceCreateUpdate(data) {
        return this.service('post', API_URL+'create', data);
    }
    servicePage(param) {
        return this.service('get', API_URL+'page?'+param);
    }
    serviceDestroy(id) {
        return this.service('delete', API_URL+'destroy/'+id);
    }
}

export default new UserService();
