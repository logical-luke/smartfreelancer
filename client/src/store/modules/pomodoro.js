import cache from "@/services/cache";

const emptyPomodoro = {
    id: null,
    startTime: null,
    endTime: null,
    breakTime: null,
    breaks: null,
};

const state = () => ({
    current: JSON.parse(JSON.stringify(emptyPomodoro)),
});

const mutations = {
    setPomodoro(state, pomodoro) {
        state.current = pomodoro;
        cache.set("pomodoro", JSON.stringify(pomodoro)).then(() => {});
    },
    clearPomodoro(state) {
        state.current = JSON.parse(JSON.stringify(emptyPomodoro));
        cache.set("pomodoro", JSON.stringify(emptyPomodoro)).then(() => {});
    },
};

const getters = {
    getPomodoro: (state) => state.current,
};

export default {
    namespaced: true,
    state,
    mutations,
    getters,
}
