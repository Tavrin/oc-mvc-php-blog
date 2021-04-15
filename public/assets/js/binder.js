"use strict";

let utils = window.utils;

class Binder {
    constructor(elem) {
        elem.dataset.target ? this.target = elem.dataset.target : this.target = null;
        elem.value ? this.value = elem.value : this.value = null;
        this.attribute = elem.dataset.targetAttribute;
        this.type = elem.dataset.type;
        elem.dataset.options ? this.options = JSON.parse(elem.dataset.options) : this.options = null;
        this.addListener(elem);
    }

    addListener(elem) {
        if (this.target) {
            this.target = document.querySelector(`#${this.target}`);
        }

        if ('text' === this.type || !this.type) {
            this.setTextEvent(elem);
        }
    }

    setTextEvent(elem) {
        elem.addEventListener('keyup', () => {
            this.value = elem.value;
            if (this.options['slugify']) {
                this.value = utils.slugify(this.value);
            }
            this.target.value = this.value;
        })
    }
}

document.querySelectorAll('.js-binder').forEach((element) => {
    new Binder(element);
})