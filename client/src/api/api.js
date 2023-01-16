import axios from "axios";

/**
 * Mocking client-server processing
 */
const _projects = [
  { 'id': 1, 'title': 'iPad 4 Mini', 'price': 500.01, 'inventory': 2 },
  { 'id': 2, 'title': 'H&M T-Shirt White', 'price': 10.99, 'inventory': 10 },
  { 'id': 3, 'title': 'Charli XCX - Sucker CD', 'price': 19.99, 'inventory': 5 }
]

export default {
  async getProjects () {
    await wait(100)
    return axios.get('http://127.0.0.1:8000/api/project')
  },
}

function wait (ms) {
  return new Promise(resolve => {
    setTimeout(resolve, ms)
  })
}
