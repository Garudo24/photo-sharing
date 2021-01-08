const state = {
    user: null
}

const getters = {}

const mutations = {
    setUser(state, user) {
        state.user = user
    }
}

const actions = {
    async register(context, data) {
        const response = await axios.post('/register', data)
        context.commit('setUser', response.data)
    },
    async login(context, data) {
        const response = await axios.post('/login', data)
        context.commit('setUser', response.data)
    },
    async logout(context) {
        const response = await axios.post('/logout')
        context.commit('setUser', null)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}