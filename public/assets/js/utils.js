"use strict";

const utils = {};

utils.addCloseEventOnParent = (e) => {
    e.currentTarget.parentNode.classList.add('d-n');
}

export {utils};