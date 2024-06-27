import cache from "@/services/cache";

const state = () => ({
  initialLoaded: false,
  synchronizationTime: null,
  queue: [],
  synchronizationLogQueue: [],
  offline: false,
  backgroundFetchingIntervalId: null,
  backgroundFetchingInProgress: false,
  backgroundUploadIntervalId: null,
  backgroundUploadInProgress: false,
  synchronizationFailed: false,
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
  setSynchronizationLogQueue(state, synchronizationLogQueue) {
    state.synchronizationLogQueue = synchronizationLogQueue;
  },
  setOffline(state, offline) {
    state.offline = offline;
  },
  setBackgroundUploadIntervalId(state, backgroundUploadIntervalId) {
    state.backgroundUploadIntervalId = backgroundUploadIntervalId;
  },
  setBackgroundFetchingIntervalId(state, backgroundFetchingIntervalId) {
    state.backgroundFetchingIntervalId = backgroundFetchingIntervalId;
  },
  setBackgroundUploadInProgress(state, backgroundUploadInProgress) {
    state.backgroundUploadInProgress = backgroundUploadInProgress;
  },
  setBackgroundFetchingInProgress(state, backgroundFetchingInProgress) {
    state.backgroundFetchingInProgress = backgroundFetchingInProgress;
  },
  clearQueue(state) {
    state.queue = [];
  },
  clearSynchronizationLogQueue(state) {
    state.synchronizationLogQueue = [];
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
  getSynchronizationLogQueue(state) {
    return state.synchronizationLogQueue;
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
  getBackgroundFetchingIntervalId(state) {
    return state.backgroundFetchingIntervalId;
  },
  isBackgroundUploadInProgress(state) {
    return state.backgroundUploadInProgress;
  },
  isBackgroundFetchingInProgress(state) {
    return state.backgroundFetchingInProgress;
  },
  isSynchronizationFailed(state) {
    return !!state.synchronizationFailed;
  },
  isBackgroundUploadEnabled(state) {
    return state.backgroundUploadIntervalId !== null;
  },
  isBackgroundFetchingEnabled(state) {
    return state.backgroundFetchingIntervalId !== null;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
