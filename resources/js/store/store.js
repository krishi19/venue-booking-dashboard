import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

axios.defaults.baseURL = process.env.MIX_APP_URL + 'api/admin/';

import modules from './modules'

Vue.use(Vuex)

export default new Vuex.Store({
    modules,
    strict: process.env.NODE_ENV !== 'production'
})
