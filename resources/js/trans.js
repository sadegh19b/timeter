import keysLodash from "lodash/keys";
import getLodash from "lodash/get";
import eachRightLodash from "lodash/eachRight";
import replaceLodash from "lodash/replace";

window.trans = window.__ = function (string, args) {
    let jsonKeys = window.i18n.json ? keysLodash(window.i18n.json) : [];
    let value;

    if (jsonKeys.find(i => i === string))
        value = getLodash(window.i18n.json, string);
    else
        value = getLodash(window.i18n, string);

    if (value === undefined)
        value = string;

    eachRightLodash(args, (paramVal, paramKey) => {
        value = replaceLodash(value, `:${paramKey}`, paramVal);
    });

    return value;
}
