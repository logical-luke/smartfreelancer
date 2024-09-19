const millisecondsPerSecond = 1000;
export default function getDateFromSecondsTimestamp(timestamp) {
  return new Date(timestamp * millisecondsPerSecond);
}
