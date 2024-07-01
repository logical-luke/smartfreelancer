import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";

const createNewTask = (newTaskInputInput) => {
    return {
        id: getUuid(),
        createdAt: store.getters["time/getServerTime"],
        name: newTaskInputInput?.name,
        description: newTaskInputInput?.description,
        clientId: newTaskInputInput?.clientId,
        projectId: newTaskInputInput?.projectId,
        parentTaskId: newTaskInputInput?.parentTaskId,
    };
}

export default {
    add(newTaskInput) {
        let tasks = JSON.parse(JSON.stringify(store.getters["tasks/getTasks"]));
        const newTask = createNewTask(newTaskInput);
        tasks.unshift(tasks);
        store.commit("tasks/setTasks", tasks);
        synchronization.pushToQueue(
            "task",
            "create",
            newTask,
        )
    },
}
