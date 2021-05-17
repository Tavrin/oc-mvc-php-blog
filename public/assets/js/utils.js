"use strict";

const utils = {};

utils.addCloseEventOnParent = (e) => {
    e.currentTarget.parentNode.classList.add('d-n');
}

utils.closeTarget = (e, target) => {
    return target.classList.add('d-n');
}

utils.getHost = () => {
    return data.host;
}

utils.slugify = text =>
    text
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w-]+/g, '')
        .replace(/--+/g, '-')

export {utils};