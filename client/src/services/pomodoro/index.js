import getRelativeTime from "@/services/time/relativeTimeGetter";
import store from "@/store";
import getUuid from "@/services/uuidGenerator";
import cache from "@/services/cache";
import synchronization from "@/services/synchronization";

export default {
    async getDurations(){
        let time = {
            hours: "00",
            minutes: "00",
            seconds: "00"
        };

        if (await this.getStartTime() && await this.getEndTime()) {
            const {
                hours,
                minutes,
                seconds
            } = getRelativeTime(await this.getStartTime(), await this.getEndTime());

            time.hours = hours;
            time.minutes = minutes;
            time.seconds = seconds;
        }

        return time;
    },
    async getStartTime() {
        const pomodoro = JSON.parse(JSON.stringify(store.getters["pomodoro/getPomodoro"]));

        return pomodoro.startTime;
    },
    async getEndTime() {
        const pomodoro = JSON.parse(JSON.stringify(store.getters["pomodoro/getPomodoro"]));

        return pomodoro.startTime + pomodoro.duration;
    },
    async isRunning() {
        const pomodoro = JSON.parse(JSON.stringify(store.getters["pomodoro/getPomodoro"]));

        return pomodoro.id && pomodoro.isRunning;
    },
    async startPomodoro(workDuration, breakDuration, repeat, type) {
        if (await this.isRunning()) {
            return;
        }

        if (type === 'fixed') {
            repeat = 0;
        }

        let newPomodoro = this.createNewPomodoro(workDuration, type);
        newPomodoro.isRunning = true;

        await store.commit("pomodoro/setPomodoro", newPomodoro);
        await cache.set("pomodoro", JSON.stringify(newPomodoro));
        // await synchronization.pushToQueue("Pomodoro", "PomodoroCreator", "CreatePomodoro", newPomodoro);

        if (repeat > 0) {
            let planned = [];
            for (let i = 0; i < repeat; i++) {
                planned.push(this.createNewPomodoro(breakDuration, 'break'));

                if (i < (repeat - 1)) {
                    planned.push(this.createNewPomodoro(workDuration, 'pomodoro'));
                }
            }
            await store.commit("pomodoro/setPlanned", planned);
            await cache.set("planned", JSON.stringify(planned));
            // await synchronization.pushToQueue("Pomodoro", "PomodoroCreator", "CreatePomodoro", newPomodoro);
        }
    },
    getEmptyPomodoro() {
        return {
            id: null,
            remainingTime: null,
            duration: null,
            isRunning: false,
            type: 'pomodoro',
        };
    },
    createNewPomodoro(duration, type) {
        let pomodoro = JSON.parse(JSON.stringify(this.getEmptyPomodoro()));
        pomodoro.id = getUuid();
        pomodoro.duration = duration;
        pomodoro.remainingTime = duration;
        pomodoro.type = type;

        return pomodoro;
    }
}
