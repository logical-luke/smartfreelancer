import getRelativeTime from "@/services/time/relativeTimeGetter";

export default {
    async getDurations(){
        let time = {
            hours: "00",
            minutes: "00",
            seconds: "00"
        };

        const {
            hours,
            minutes,
            seconds
        } = getRelativeTime(await this.getStartTime(), await this.getEndTime());

        time.hours = hours;
        time.minutes = minutes;
        time.seconds = seconds;

        return time;
    }
}
