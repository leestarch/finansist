import request from '../api/request'

class Resource {
    constructor(url) {
        this.url = url
    }

    list(query) {
        alert('sadasds')
        return request({
            url: '/' + this.url,
            method: 'get',
            params: query,
        })
    }

    get(id) {
        return request({
            url: '/' + this.url + '/' + id,
            method: 'get',
        })
    }

    store(resource) {
        return request({
            url: '/' + this.url,
            method: 'post',
            data: resource,
        })
    }

    update(id, resource) {
        return request({
            url: '/' + this.url + '/' + id,
            method: 'put',
            data: resource,
        })
    }

    delete(id) {
        return request({
            url: '/' + this.url + '/' + id,
            method: 'delete',
        })
    }
}

export {Resource as default}
