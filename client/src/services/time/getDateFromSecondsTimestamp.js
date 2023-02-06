export default function getDateFromSecondsTimestamp(timestamp) {
  return new Date(timestamp * 1000);
}
