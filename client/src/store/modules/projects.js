import api from '../../api/api'

// initial state
const state = () => ({
    all: []
})

// getters
const getters = {}

// actions
const actions = {
    async getAllProjects ({ commit }) {
        const projects = await api.getProjects()
        commit('setProjects', projects)
    }
}

// mutations
const mutations = {
    setProjects (state, projects) {
        state.all = projects
    },

    decrementProjectInventory (state, { id }) {
        const project = state.all.find(project => project.id === id)
        project.inventory--
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
