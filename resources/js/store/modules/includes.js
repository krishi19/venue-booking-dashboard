import axios from "axios";
const state = {
	allLocales:[{id:1,name:'English'},{id:2,name:'Nepali'}],
	overlay:false

}

const mutations = {
	TOGGLE_OVERLAY(state){
		state.overlay=!state.overlay
	},

}

const actions = {

}
const getters = {
	overlay: state => state.overlay,
	allLocales:state=>state.allLocales
}

export default {
	state,
	mutations,
	actions,
	getters
}
