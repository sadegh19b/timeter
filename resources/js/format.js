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
    // format number 1000000 to 1,234,567
    return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
