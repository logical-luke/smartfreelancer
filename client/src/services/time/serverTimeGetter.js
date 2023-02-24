import api from "@/services/api";

const millisecondsPerSecond = 1000;

export default async function getServerTime() {
  const requestTime = Math.round(Date.now() / millisecondsPerSecond);
  const serverTime = await api.getServerTime();
  const responseTime = Math.round(Date.now() / millisecondsPerSecond);
  const returnTime = responseTime - requestTime;

  return serverTime - returnTime;
}
