import api from "@/services/api/api";

export async function getServerTime() {
  const requestTime = Math.round(Date.now() / 1000);
  const serverTime = await api.getServerTime();
  const responseTime = Math.round(Date.now() / 1000);
  const returnTime = responseTime - requestTime;

  return serverTime - returnTime;
}