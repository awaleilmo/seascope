class decode{
    base64(base64) {
        return atob(base64)
    }
    base64toJSON(base64) {
        return JSON.parse(this.base64(base64))
    }
}

export default new decode()
