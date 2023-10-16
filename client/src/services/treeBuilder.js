export default function buildWorkHubTree(clients, projects, tasks) {
    let arr = [...clients, ...projects, ...tasks];
    let tree = [];
    let map = new Map(arr.map(i => [i.id, {...i, children: [], type: getType(i, clients, projects, tasks)}]));

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

    return tree;
};

function getType(obj, clients, projects, tasks) {
    if (clients.find(client => client.id === obj.id)) {
        return "client";
    } else if (projects.find(project => project.id === obj.id)) {
        return "project";
    } else if (tasks.find(task => task.id === obj.id)) {
        return "task";
    } else {
        throw new Error("Invalid object type encountered.");
    }
}
