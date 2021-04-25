"use strict";

let utils = window.utils;

class Filler {
    constructor(elem) {
        elem.dataset.target ? this.target = elem.dataset.target : this.target = null;
        elem.value ? this.value = elem.value : this.value = null;
        this.type = elem.dataset.type;
        this.id = elem.dataset.id;
        elem.dataset.class ? this.class = elem.dataset.class : this.class = '';
        elem.dataset.src ? this.src = elem.dataset.src : this.src = '//:0';
        elem.dataset.options ? this.options = JSON.parse(elem.dataset.options) : this.options = null;
        this.fillElement(elem);
    }

    fillElement(elem) {
        if ('image' === this.type) {
            this.fillEmptyImage(elem);
        }
    }

    fillEmptyImage(elem) {
        let item = document.createElement('img');
        item.id = this.id;
        item.alt= "Image preview";
        item.src = this.src;
        item.classList = (this.class);
        if ('http://:0/' === item.src) {
            item.classList.add('d-n');
        }

        elem.appendChild(item);
    }
}

document.querySelectorAll('.js-filler').forEach((element) => {
    new Filler(element);
})