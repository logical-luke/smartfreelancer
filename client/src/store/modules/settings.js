const state = () => ({
    navBarCollapsed: false,
    locale: "en",
    timerMode: "timer",
});

const actions = {
    setLocale({commit}, locale) {
        commit("setLocale", locale);
    },
};

const mutations = {
    setNavbarCollapsed: (state, collapsed) => (state.navBarCollapsed = collapsed),
    toggleNavBarCollapsed: (state) =>
        (state.navBarCollapsed = !state.navBarCollapsed),
    setLocale(state, locale) {
        state.locale = locale;
    },
    toggleTimerMode: (state) => (state.timerMode = state.timerMode === "timer" ? "manual" : "timer"),
    setTimerMode: (state, mode) => (state.timerMode = mode),
};

const getters = {
    isNavBarCollapsed: (state) => state.navBarCollapsed,
    getLocale: (state) => state.locale,
    getTimerMode: (state) => state.timerMode,
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters,
};
