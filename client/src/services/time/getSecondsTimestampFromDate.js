const millisecondsPerSecond = 1000;
export default function getSecondsTimestampFromDate(date) {
  const dateObject = new Date(date);
  const millisecond = dateObject.getTime();

  return millisecond / millisecondsPerSecond;
}
