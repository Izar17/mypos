export default class Url {
    url;
    constructor() {
        this.url = ns.base_url;
    }
    get(path) {
        return this.url + path;
    }
}
//# sourceMappingURL=url.js.map