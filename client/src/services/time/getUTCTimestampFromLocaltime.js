const millisecondsInSecond = 1000;
const millisecondsInMinute = millisecondsInSecond * 60;

export default function getUTCTimestampFromLocaltime() {
  const localTime = new Date();
  return Math.round(
    new Date(
      Date.now() + localTime.getTimezoneOffset() * millisecondsInMinute
    ) / millisecondsInSecond
  );
}
