export const formatNumber = (number, decimals = 2) => {
    return number.toLocaleString(undefined, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
        useGrouping: false,
    });
};

export const formatThousands = (number, decimals = 2) => {
    const locale = window.navigator.language;
    return Number(number).toLocaleString(locale, {
        useGrouping: true,
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
};
