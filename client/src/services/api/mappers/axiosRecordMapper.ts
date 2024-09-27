export function toAxiosData<T extends Record<string, any>>(data: T): Record<string, unknown> {
  return Object.entries(data).reduce((acc, [key, value]) => {
    acc[key] = value;
    return acc;
  }, {} as Record<string, unknown>);
}