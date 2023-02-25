const state = () => ({
  initialLoaded: false,
  synchronizationTime: null,
  queue: [],
  isOffline: false,
  backgroundUploadIntervalId: null,
});

const mutations = {
  setInitialLoaded(state, loaded) {
    state.initialLoaded = loaded;
  },
  setSynchronizationTime(state, synchronizationTime) {
    state.synchronizationTime = synchronizationTime;
  },
  setQueue(state, queue) {
    state.queue = queue;
  },
  setIsOffline(state, isOffline) {
    state.isOffline = isOffline;
  },
  setBackgroundUploadIntervalId(state, backgroundUploadIntervalId) {
    state.backgroundSyncIntervalId = backgroundUploadIntervalId;
  }
};

const getters = {
  isInitialLoaded(state) {
    return state.initialLoaded;
  },
  getSynchronizationTime(state) {
    return state.synchronizationTime;
  },
  isSynchronized(state) {
    return state.synchronizationTime && state.queue.length === 0;
  },
  getQueue(state) {
    return state.queue;
  },
  isOffline(state) {
    return state.isOffline;
  },
  isQueueEmpty(state) {
    return state.queue.length === 0;
  },
  getBackgroundUploadIntervalId(state) {
    return state.backgroundSyncIntervalId;
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
