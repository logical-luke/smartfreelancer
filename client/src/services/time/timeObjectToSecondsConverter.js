const secondsInMinute = 60;
const secondsInHour = secondsInMinute * 60;

export default function timeObjectToSecondsConverter (timeObject) {
    let seconds = 0;

    if (timeObject.hours) {
        seconds += timeObject.hours * secondsInHour;
    }

    if (timeObject.minutes) {
        seconds += timeObject.minutes * secondsInMinute;
    }

    if (timeObject.seconds) {
        seconds += timeObject.seconds;
    }

    return seconds;
}
