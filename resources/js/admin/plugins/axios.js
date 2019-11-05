import Axios from 'axios';

Axios.defaults.baseURL = '/admin/api/';
Axios.interceptors.request.use(config => {
    if (['get', 'post'].indexOf(config.method.toLowerCase()) === -1) {
        if (config.data instanceof FormData) {
            config.data.set('_method', config.method);
        } else if (typeof config.data === 'string') {
            config.data += '&_method=' + encodeURIComponent(config.method);
        } else if (typeof config.data === 'object' && config.data !== null) {
            config.data._method = config.method;
        } else if (config.data === null || config.data === undefined) {
            config.data = {
                _method: config.method,
            };
        }
        config.method = 'post';
    }
    return config;
});
