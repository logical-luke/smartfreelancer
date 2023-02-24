import { openDB } from 'idb';

const dbPromise = openDB('smartfreelancer-database', 1, {
  upgrade(db) {
    db.createObjectStore('main-store', { keyPath: 'id' });
  },
});

export default {
  async set(key, value) {

  },
  async get(key) {

  },
  async keys() {

  },
  async remove(key) {

  },
  async clear() {

  },
}
