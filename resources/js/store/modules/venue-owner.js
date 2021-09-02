import axios from "axios";

const state = {
    getAllAdmins: [],
};
const mutations = {
    SET_ALL_ADMINS(state, payload) {
        state.getAllAdmins = payload;
    },

    SET_CREATED_ADMIN(state, payload) {
        if (state.getAllAdmins.length)
            state.getAllAdmins.unshift(payload);
        else
            state.getAllAdmins.push(payload);
    },
    SET_UPDATED_ADMIN(state, payload) {
        let index=state.getAllAdmins.findIndex(x=>x.id==payload.id);
        if (index>-1)
        state.getAllAdmins.splice(index,1,payload);
    },
    SET_DELETED_ADMIN(state, payload) {
        let index=state.getAllAdmins.findIndex(x=>x.id==payload.id);
        if (index>-1)
            state.getAllAdmins.splice(index,1);
    },
};
const actions = {
    ALL_ADMINS(context,payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("admins",payload)
                .then(response => {
                    context.commit("SET_ALL_ADMINS", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    CREATE_ADMIN(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("admins", payload)
                .then(response => {
                    context.commit("SET_CREATED_ADMIN", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    UPDATE_ADMIN(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .put("admins/" + payload.id, payload)
                .then(response => {
                    context.commit("SET_UPDATED_ADMIN", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    DELETE_ADMIN(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .delete("admins/" + payload.id)
                .then(response => {
                    context.commit("SET_DELETED_ADMIN", payload);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },

};
const getters = {
    getAllAdmins: state => state.getAllAdmins,
};
export default {
    state,
    mutations,
    getters,
    actions
};
