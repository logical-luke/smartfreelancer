import { NTPClient } from "ntpclient";

const ntpClient = new NTPClient();

export async function getNetworkTime() {
  return await ntpClient.getNetworkTime();
}
