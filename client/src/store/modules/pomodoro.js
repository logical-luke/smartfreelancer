import cache from "@/services/cache";
import pomodoro from "@/services/pomodoro";

const state = () => ({
    current: JSON.parse(JSON.stringify(pomodoro.getEmptyPomodoro())),
    planned: [],
});

const mutations = {
    setPomodoro(state, pomodoro) {
        state.current = pomodoro;
        cache.set("pomodoro", JSON.stringify(pomodoro)).then(() => {});
    },
    clearPomodoro(state) {
        const emptyPomodoro = pomodoro.getEmptyPomodoro();
        state.current = JSON.parse(JSON.stringify(emptyPomodoro));
        cache.set("pomodoro", JSON.stringify(emptyPomodoro)).then(() => {});
    },
    setPlanned(state, planned) {
        state.planned = planned;
        cache.set("planned", JSON.stringify(planned)).then(() => {});
    },
    clearPlanned(state) {
        state.planned = [];
        cache.set("planned", JSON.stringify(planned)).then(() => {});
    },
    addPlanned(state, planned) {
        state.planned.push(planned);
        cache.set("planned", JSON.stringify(planned)).then(() => {});
    },
    removedPlanned(state, id) {
        state.planned = state.planned.filter((p) => p.id!== id);
        cache.set("planned", JSON.stringify(state.planned)).then(() => {});
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
