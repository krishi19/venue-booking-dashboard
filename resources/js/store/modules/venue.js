import axios from "axios";

const state = {
    getAllVenues: [],
};
const mutations = {
    SET_ALL_VENUES(state, payload) {
        state.getAllVenues = payload;
    },

    SET_CREATED_VENUE(state, payload) {
        if (state.getAllVenues.length)
            state.getAllVenues.unshift(payload);
        else
            state.getAllVenues.push(payload);
    },
    SET_UPDATED_VENUE(state, payload) {
        let index=state.getAllVenues.findIndex(x=>x.id==payload.id);
        if (index>-1)
        state.getAllVenues.splice(index,1,payload);
    },
    SET_DELETED_VENUE(state, payload) {
        let index=state.getAllVenues.findIndex(x=>x.id==payload.id);
        if (index>-1)
            state.getAllVenues.splice(index,1);
    },
};
const actions = {
    ALL_VENUES(context,payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("venues",payload)
                .then(response => {
                    context.commit("SET_ALL_VENUES", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    CREATE_VENUE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("venues", payload)
                .then(response => {
                    context.commit("SET_CREATED_VENUE", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },VENUE_DETAIL(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("venues/"+payload.id)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    UPDATE_VENUE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("venues/" + payload.id, payload.form)
                .then(response => {
                    // context.commit("SET_UPDATED_VENUE", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    DELETE_VENUE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .delete("venues/" + payload.id)
                .then(response => {
                    context.commit("SET_DELETED_VENUE", payload);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },

};
const getters = {
    getAllVenues: state => state.getAllVenues,
};
export default {
    state,
    mutations,
    getters,
    actions
};
