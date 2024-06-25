import cache from "@/services/cache";

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
  removeFromQueue({ commit, rootGetters }, queueItem) {
    const queue = JSON.parse(
      JSON.stringify(rootGetters["synchronization/getQueue"])
    );
    queue.splice(
      queue.findIndex((obj) => obj.id === queueItem.id),
      1
    );
    commit("setQueue", queue);
  },
};

const mutations = {
  setInitialLoaded(state, loaded) {
    state.initialLoaded = loaded;
  },
  setSynchronizationTime(state, synchronizationTime) {
    state.synchronizationTime = synchronizationTime;
    cache
      .set("synchronizationTime", JSON.stringify(synchronizationTime))
      .then();
  },
  setQueue(state, queue) {
    state.queue = queue;
    cache.set("queue", JSON.stringify(queue)).then();
  },
  setOffline(state, offline) {
    state.offline = offline;
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
    cache.set("queue", JSON.stringify([])).then();
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
    return state.backgroundUploadIntervalId;
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
  isBackgroundUploadEnabled(state) {
    return state.backgroundUploadIntervalId !== null;
  },
  isBackgroundSyncEnabled(state) {
    return state.backgroundSyncIntervalId !== null;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
};
