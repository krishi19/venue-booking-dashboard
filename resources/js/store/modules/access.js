import axios from "axios";
const state = {
	token: localStorage.getItem('token'),
	user: localStorage.getItem('user'),
	role: localStorage.getItem('role'),

}

const mutations = {
	setToken(state, payload) {
		state.token = localStorage.getItem('token');
		state.role = localStorage.getItem('role');
		// state.user = payload.user;
	},
	setLogOut(state) {
		state.token = ''
		state.user = ''
	},
}

const actions = {
	// async signUp({commit}, payload) {
	//     let response = await axios.post(`register`, payload)
	//     const token = 'Bearer ' + response.data.access_token
	//     localStorage.setItem('token', token)
	//     commit('setToken', token)
	// },

	async loginAttempt({commit}, payload) {
		return new Promise((resolve, reject) => {
			axios.post('login', payload)
				.then(response => {

					const data = response.data;
					// if (data !== 400) {

						// console.log(data.user.roles[0].name);
						// debugger
						// const role = data.user.roles[0].name;
						// localStorage.setItem('userRole', role)
						const token = 'Bearer '+data.token
						localStorage.setItem('token', token)
						localStorage.setItem('role', data.role)
						// const user = data.user.id
						// localStorage.setItem('user', user)
						commit('setToken', token)
						// localStorage.setItem(
						// 	"roles",
						// 	JSON.stringify(data.roles)
						// );
						// localStorage.setItem(
						// 	"permissions",
						// 	JSON.stringify(data.permissions)
						// );
						// localStorage.setItem(
						// 	"activity",
						// 	data.activity_id
						// );
					// }
					resolve(response)
				}).catch(error => {
				reject(error)
			})
		})

	},

	logOut({commit}) {
		localStorage.removeItem('token')
		localStorage.removeItem('roles')
		commit('setLogOut')
	}
}
const getters = {
	isLoggedIn: state => !!state.token,
	role: state => state.role,
	// userDetails: state => state.user,
	// companyDetails: state => state.company,
	// companyId:state=>state.company_id
}

export default {
	state,
	mutations,
	actions,
	getters
}
