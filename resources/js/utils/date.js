/**
 *
 * @param {string} date
 * @param {string} format - locale format (default "en-GB")
 * @returns {string} formatted date based on the locale (default "ddd, DD MMM YYYY, HH:mm")
 */
export const formatDate = (date, format = "en-GB") => {
    return new Date(date).toLocaleString(format, {
        month: "short",
        day: "2-digit",
        year: "numeric",
        weekday: "short",
        hour: "2-digit",
        minute: "numeric",
    });
};
