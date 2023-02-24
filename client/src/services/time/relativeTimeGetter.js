const MILLISECONDS_PER_SECOND = 1000;
const SECONDS_PER_MINUTE = 60;
const MINUTES_PER_HOUR = 60;

export default function getRelativeTime(startTime, endTime) {
  if (typeof startTime === "number") {
    startTime = new Date(startTime * MILLISECONDS_PER_SECOND);
  }
  if (typeof endTime === "number") {
    endTime = new Date(endTime * MILLISECONDS_PER_SECOND);
  }

  const timeDiff = Math.abs(endTime.getTime() - startTime.getTime());
  const hours = Math.floor(
    timeDiff / (MILLISECONDS_PER_SECOND * SECONDS_PER_MINUTE * MINUTES_PER_HOUR)
  );
  const minutes = Math.floor(
    (timeDiff / (MILLISECONDS_PER_SECOND * SECONDS_PER_MINUTE)) %
      MINUTES_PER_HOUR
  );
  const seconds = Math.floor(
    (timeDiff / MILLISECONDS_PER_SECOND) % SECONDS_PER_MINUTE
  );
  return {
    hours: hours.toString().padStart(2, "0"),
    minutes: minutes.toString().padStart(2, "0"),
    seconds: seconds.toString().padStart(2, "0"),
  };
}
