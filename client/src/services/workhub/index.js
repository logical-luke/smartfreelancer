export default {
    getTree(clients, projects, tasks) {
        let arr = [...clients, ...projects, ...tasks];
        let tree = [];
        let map = new Map(arr.map(i => [i.id, { ...i, children: [] }]));

        map.forEach((i, key) => {
            if (i.clientId) {
                map.get(i.clientId).children.push(map.get(key));
            } else if (i.projectId) {
                map.get(i.projectId).children.push(map.get(key));
            } else if (i.taskId) {
                map.get(i.taskId).children.push(map.get(key));
            } else {
                tree.push(map.get(key));
            }
        });

        console.log(tree);

        return tree;
    }
};
