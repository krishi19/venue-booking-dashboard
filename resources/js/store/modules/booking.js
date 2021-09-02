import axios from "axios";

const state = {
    getAllBookings: [],
};
const mutations = {
    SET_ALL_BOOKINGS(state, payload) {
        state.getAllBookings = payload;
    },
    SET_UPDATED_BOOKING(state, payload) {
        let index=state.getAllBookings.findIndex(x=>x.id==payload.id);
        if (index>-1)
        state.getAllBookings.splice(index,1,payload);
    },
};
const actions = {
    ALL_BOOKINGS(context,payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("bookings",payload)
                .then(response => {
                    context.commit("SET_ALL_BOOKINGS", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    BOOKING_DETAIL(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("bookings/"+payload.id)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    UPDATE_BOOKING(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .put("bookings/" + payload.id, payload)
                .then(response => {
                    context.commit("SET_UPDATED_BOOKING", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
};
const getters = {
    getAllBookings: state => state.getAllBookings,
};
export default {
    state,
    mutations,
    getters,
    actions
};
