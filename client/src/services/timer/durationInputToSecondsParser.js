const secondsInMinute = 60;
const secondsInHour = secondsInMinute * 60;

export default function durationInputToSecondsParser(durationInput) {
    const durationParts = durationInput.split(":");

    if (durationParts.length === 1) {
        return parseInt(durationParts[0]) * secondsInMinute;
    }

    if (durationParts.length === 2) {
        return parseInt(durationParts[0]) * secondsInHour + parseInt(durationParts[1]) * secondsInMinute;
    }

    return parseInt(durationParts[0]) * secondsInHour + parseInt(durationParts[1]) * secondsInMinute + parseInt(durationParts[2]);
}
