const millisecondsPerSecond = 1000;
const secondsPerMinute = 60;
const minutesPerHour = 60;

export default function getRelativeTime(startTime, endTime) {
  if (typeof startTime === "number") {
    startTime = new Date(startTime * millisecondsPerSecond);
  }
  if (typeof endTime === "number") {
    endTime = new Date(endTime * millisecondsPerSecond);
  }

  const timeDiff = Math.abs(endTime.getTime() - startTime.getTime());
  const hours = Math.floor(
    timeDiff / (millisecondsPerSecond * secondsPerMinute * minutesPerHour)
  );
  const minutes = Math.floor(
    (timeDiff / (millisecondsPerSecond * secondsPerMinute)) % minutesPerHour
  );
  const seconds = Math.floor(
    (timeDiff / millisecondsPerSecond) % secondsPerMinute
  );
  return {
    hours: hours.toString().padStart(2, "0"),
    minutes: minutes.toString().padStart(2, "0"),
    seconds: seconds.toString().padStart(2, "0"),
  };
}
