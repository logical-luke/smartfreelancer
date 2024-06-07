import cache from "@/services/cache";
import pomodoro from "@/services/pomodoro";

const state = () => ({
  current: null,
  planned: [],
  configuration: JSON.parse(JSON.stringify(pomodoro.getDefaultConfiguration())),
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
    cache.set("planned", JSON.stringify([])).then(() => {});
  },
  addPlanned(state, planned) {
    state.planned.push(planned);
    cache.set("planned", JSON.stringify(planned)).then(() => {});
  },
  removedPlanned(state, id) {
    state.planned = state.planned.filter((p) => p.id !== id);
    cache.set("planned", JSON.stringify(state.planned)).then(() => {});
  },
  setConfiguration(state, configuration) {
    state.configuration = configuration;
    cache
      .set("pomodoro-configuration", JSON.stringify(configuration))
      .then(() => {});
  },
};

const getters = {
  getPomodoro: (state) => state.current,
  getPlanned: (state) => state.planned,
  getConfiguration: (state) => state.configuration,
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
