import { createStore, createLogger } from 'vuex'
import projects from './modules/projects'

const debug = import.meta.env.NODE_ENV !== 'production'

export default createStore({
    modules: {
        projects
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
})
