import _ from 'lodash';
window._ = _;

import 'bootstrap/dist/css/bootstrap.min.css';

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
