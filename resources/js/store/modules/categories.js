import axios from "axios";

const state = {
    allCategories: [],
    selectedCategory: null,
    bothCategories: []
};
const mutations = {
    SET_ALL_CATEGORY(state, payload) {
        state.allCategories = payload;
    },
    SET_BOTH_CATEGORY(state, payload) {
        state.bothCategories = payload;
    },
    SET_CREATED_CATEGORY(state, payload) {
        if (state.allCategories.length)
            state.allCategories.unshift(payload);
        else
            state.allCategories.push(payload);
        if (payload.category_id){
            let index=state.allCategories.findIndex(x=>x.id==payload.category_id)
            if (index>-1){
                state.allCategories[index].sub_categories_count++
            }
        }
    },
    SET_UPDATED_CATEGORY(state, payload) {
        let index=state.allCategories.findIndex(x=>x.id==payload.id);
        if (index>-1)
        state.allCategories.splice(index,1,payload);
    },
    SET_DELETED_CATEGORY(state, payload) {
        let index=state.allCategories.findIndex(x=>x.id==payload.id);
        if (index>-1)
            state.allCategories.splice(index,1);
        if (payload.category_id) {
            let index = state.allCategories.findIndex(x => x.id == payload.category_id)
            if (index > -1) {
                state.allCategories[index].sub_categories_count--
            }
        }
    },
    SET_SELECTED_CATEGORY(state, payload) {
        state.selectedCategory = payload;
    }
};
const actions = {
    CATEGORY_SUB_CATEGORY({ commit }) {
        return new Promise((resolve, reject) => {
            axios
                .get("category/parent_sub_category")
                .then(res => {
                    commit("SET_BOTH_CATEGORY", res.data);
                    resolve(res);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    ALL_CATEGORY(context,payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("categories",payload)
                .then(response => {
                    context.commit("SET_ALL_CATEGORY", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    ALL_PARENT_CATEGORY(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("category/parent_category/" + payload)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    CREATE_CATEGORY(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("categories", payload)
                .then(response => {
                    context.commit("SET_CREATED_CATEGORY", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    UPDATE_CATEGORY(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("categories/" + payload.id, payload.form)
                .then(response => {
                    context.commit("SET_UPDATED_CATEGORY", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    UPDATE_CATEGORY_STATUS(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .put("categories/" + payload.id, payload)
                .then(response => {
                    context.commit("SET_UPDATED_CATEGORY", response.data.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    DELETE_CATEGORY(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .delete("categories/" + payload.id)
                .then(response => {
                    context.commit("SET_DELETED_CATEGORY", payload);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    SELECTED_CATEGORY(context, payload) {
        context.commit("SET_SELECTED_CATEGORY", payload);
    }
};
const getters = {
    getAllCategories: state => state.allCategories,
    getSelectedCategory: state => state.selectedCategory,
    getBothCategories: state => state.bothCategories
};
export default {
    state,
    mutations,
    getters,
    actions
};
