type MapperConfigSetting<T, K extends keyof T> = {
    key: string;
    required?: boolean;
    default?: T[K];
    transform?: (value: any) => T[K];
};

export type MapperConfig<T> = {
    [K in keyof T]: MapperConfigSetting<T, K>;
};

export function mapApiData<T>(data: any, config: MapperConfig<T>): T {
    const result = {} as T;

    for (const [key, settings] of Object.entries(config) as [keyof T, MapperConfigSetting<T, keyof T>][]) {
        const apiKey = settings.key;
        let value = data[apiKey];

        if (value === undefined) {
            if (settings.required) {
                throw new Error(`Required property '${apiKey}' is missing from API response`);
            }
            value = settings.default;
        }

        if (settings.transform) {
            value = settings.transform(value);
        }

        result[key] = value;
    }

    return result;
}