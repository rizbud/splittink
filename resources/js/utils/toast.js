import { toast as toastify } from "vue3-toastify";

export const toast = {
    success: (message) => {
        toastify(message, {
            type: "success",
        });
    },
    error: (message) => {
        toastify(message, {
            type: "error",
        });
    },
    warning: (message) => {
        toastify(message, {
            type: "warning",
        });
    },
    info: (message) => {
        toastify(message, {
            type: "info",
        });
    },
};
