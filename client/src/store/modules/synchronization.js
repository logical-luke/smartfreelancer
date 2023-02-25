const state = () => ({
  initialLoaded: false,
  synchronizationTime: null,
  queue: [],
  offline: false,
  backgroundUploadIntervalId: null,
  backgroundDownloadIntervalId: null,
  backgroundUploadInProgress: false,
  backgroundDownloadInProgress: false,
  synchronizationFailed: false,
});

const actions = {
    pushToQueue({ commit, rootGetters }, queueItem) {
        const queue = JSON.parse(JSON.stringify(rootGetters["synchronization/getQueue"]));
        queue.push(queueItem);
        commit("setQueue", queue);
    },
    removeFromQueue({ commit, rootGetters }, queueItem) {
        const queue = JSON.parse(JSON.stringify(rootGetters["synchronization/getQueue"]));
        queue.splice(queue.indexOf(queueItem), 1);
        commit("setQueue", queue);
    },
};

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
  setOffline(state, offline) {
    state.offline = isOffline;
  },
  setBackgroundUploadIntervalId(state, backgroundUploadIntervalId) {
    state.backgroundSyncIntervalId = backgroundUploadIntervalId;
  },
  setBackgroundDownloadIntervalId(state, backgroundDownloadIntervalId) {
    state.backgroundSyncIntervalId = backgroundDownloadIntervalId;
  },
  setBackgroundUploadInProgress(state, backgroundUploadInProgress) {
    state.backgroundUploadInProgress = backgroundUploadInProgress;
  },
  setBackgroundDownloadInProgress(state, backgroundDownloadInProgress) {
    state.backgroundDownloadInProgress = backgroundDownloadInProgress;
  },
  clearQueue(state) {
    state.queue = [];
  },
  setSynchronizationFailed(state, synchronizationFailed) {
    state.synchronizationFailed = synchronizationFailed;
  },
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
    return state.offline;
  },
  isQueueEmpty(state) {
    return state.queue.length === 0;
  },
  getBackgroundUploadIntervalId(state) {
    return state.backgroundSyncIntervalId;
  },
  getBackgroundDownloadIntervalId(state) {
    return state.backgroundSyncIntervalId;
  },
  isBackgroundUploadInProgress(state) {
    return state.backgroundUploadInProgress;
  },
  isBackgroundDownloadInProgress(state) {
    return state.backgroundDownloadInProgress;
  },
  isSynchronizationFailed(state) {
    return state.synchronizationFailed;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
};
