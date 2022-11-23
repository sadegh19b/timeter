export const format = (node, formatFunction) => {
    function updateValue(e) {
        node.value = formatFunction(node.value)
    }

    node.addEventListener('input', updateValue)
    node.addEventListener('paste', updateValue)

    // Format on intial hydration
    node.value = formatFunction(node.value)

    return {
        destroy() {
            node.removeEventListener('input', updateValue)
            node.removeEventListener('paste', updateValue)
        }
    }
}

export const numberFormat = value => {
    // format number 1000000 to 1,234,567 and accept `.` for ex 1.50
    return value.replace(/\D\./g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

export const datetimeFormat = value => {
    if (value.length > 16) {
        return value.slice(0, 16);
    }

    let result = value.replace(/[^0-9-:\s]/g, '');
    switch (value.length) {
        case 1:
            if (!isInteger(result)) {
                result = '';
            }
            if (result === '0') {
                result = "1";
            }
            break;
        case 2:
            if (!isInteger(result)) {
                result = result[0];
            }
            break;
        case 3:
            if (!isInteger(result)) {
                result = result.slice(0, 2);
            }
            break;
        case 4:
            if (!isInteger(result)) {
                result = result.slice(0, 3);
            }
            break;
        case 5:
            if (result[4] === " " || result[4] === ":") {
                result = result.slice(0, 4);
                break;
            }
            if (result[4] !== "-" && result[4] !== " ") {
                result = result.slice(0, 4) + "-" + result[4];
            }
            if (result[5] > '1') {
                result = result.slice(0, 5) + "0";
            }
            break;
        case 6:
            if (!isInteger(result[5])) {
                result = result.slice(0, 5);
            }
            if (result[5] > '1') {
                result = result.slice(0, 5) + "0";
            }
            break;
        case 7:
            if (!isInteger(result[6])) {
                result = result.slice(0, 6);
            }
            if (result.slice(5, 7) === '00') {
                result = result.slice(0, 5) + "01";
            }
            if (result.slice(5, 7) > '12') {
                result = result.slice(0, 5) + "12";
            }
            break;
        case 8:
            if (result[7] === " " || result[7] === ":") {
                result = result.slice(0, 7);
                break;
            }
            if (result[7] !== "-") {
                result = result.slice(0, 7) + "-" + result[7];
            }
            if (result[8] > '3') {
                result = result.slice(0, 8) + "3";
            }
            break;
        case 9:
            if (!isInteger(result[8])) {
                result = result.slice(0, 8);
            }
            if (result[8] > '3') {
                result = result.slice(0, 8) + "3";
            }
            break;
        case 10:
            if (result.slice(8, 10) === '00') {
                result = result.slice(0, 8) + "01";
            }
            if (result.slice(8, 10) > '31') {
                result = result.slice(0, 8) + "31";
            }
            break;
        case 11:
            if (result[10] === "-" || result[10] === ":") {
                result = result.slice(0, 10);
                break;
            }
            if (result[10] !== " ") {
                result = result.slice(0, 10) + " " + result[10];
            }
            if (result[11] > '3') {
                result = result.slice(0, 11) + "0";
            }
            break;
        case 12:
            if (!isInteger(result[11])) {
                result = result.slice(0, 11);
            }
            if (result[11] > '3') {
                result = result.slice(0, 11) + "0";
            }
            break;
        case 13:
            if (!isInteger(result[12])) {
                result = result.slice(0, 12);
            }
            if (result.slice(11, 13) > '23') {
                result = result.slice(0, 11) + "23";
            }
            break;
        case 14:
            if (result[13] === "-" || result[13] === " ") {
                result = result.slice(0, 13);
                break;
            }
            if (result[13] !== ":") {
                result = result.slice(0, 13) + ":" + result[13];
            }
            if (result[14] > '5') {
                result = result.slice(0, 14) + "0";
            }
            break;
        case 15:
            if (!isInteger(result[14])) {
                result = result.slice(0, 14);
            }
            if (result[14] > '5') {
                result = result.slice(0, 14) + "0";
            }
            break;
        case 16:
            if (!isInteger(result[15])) {
                result = result.slice(0, 15);
            }
            break;
        default:
            break;
    }

    return result;
    //return value.replace(/\B([1-9]\d{2})-?(0[1-9]|1[0-2])-?(0[1-9]|[12]\d|3[01])\s?(2[0-3]|[01][0-9]):?([0-5][0-9])/g, "$1-$2-$3 $4:$5");
}

const isInteger = num => /^[0-9]+$/.test(num+'');
