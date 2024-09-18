import pomodoro from "@/services/pomodoro";

const state = () => ({
  current: null,
  planned: [],
  configuration: JSON.parse(JSON.stringify(pomodoro.getDefaultConfiguration())),
});

const mutations = {
  setPomodoro(state, pomodoro) {
    state.current = pomodoro;
  },
  clearPomodoro(state) {
    const emptyPomodoro = pomodoro.getEmptyPomodoro();
    state.current = JSON.parse(JSON.stringify(emptyPomodoro));
  },
  setPlanned(state, planned) {
    state.planned = planned;
  },
  clearPlanned(state) {
    state.planned = [];
  },
  addPlanned(state, planned) {
    state.planned.push(planned);
  },
  removedPlanned(state, id) {
    state.planned = state.planned.filter((p) => p.id !== id);
  },
  setConfiguration(state, configuration) {
    state.configuration = configuration;
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
