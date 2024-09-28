import type { Client, ClientForm } from '@/interfaces/Client';

export default function clientToClientForm(client: Client): ClientForm {
  return {
    name: client.name,
    avatar: client.avatar,
    phone: client.phone,
    email: client.email,
  };
}
