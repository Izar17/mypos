import nsSelectPopupVue from "~/popups/ns-select-popup.vue";
import { Popup } from "./popup";
import { joinArray } from "./join-array";
export async function selectApiEntities(resource, label, value, type = 'multiselect') {
    return await new Promise((resolve, reject) => {
        nsHttpClient.get(resource)
            .subscribe({
            next: async (resources) => {
                try {
                    const result = (await new Promise((resolve, reject) => {
                        Popup.show(nsSelectPopupVue, {
                            label,
                            type,
                            options: resources.map(resource => {
                                return {
                                    label: resource.name,
                                    value: resource.id
                                };
                            }),
                            value,
                            resolve,
                            reject
                        });
                    }));
                    if (type === 'multiselect') {
                        const labels = resources
                            .filter(resource => result.includes(resource.id))
                            .map(resource => resource.name);
                        return resolve({
                            labels: joinArray(labels),
                            values: result
                        });
                    }
                    else {
                        const labels = resources
                            .filter(resource => +resource.id === +result)
                            .map(resource => resource.name);
                        return resolve({ labels, values: [result] });
                    }
                }
                catch (exception) {
                    return reject(exception);
                }
            },
            error: error => {
                return reject(error);
            }
        });
    });
}
//# sourceMappingURL=select-api-entities.js.map