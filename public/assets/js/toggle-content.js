"use strict";

class ToggleContent {
    constructor() {
        this.elems = [];
        this.addListeners();
    }

    addListeners() {
        document.querySelectorAll('.js-toggle').forEach((element) => {
            if ('display' === element.dataset.type) {
                this.setDisplayEvent(element);
            }
        })
    }

    setDisplayEvent(elem) {
        elem.addEventListener('click', ()=> {
            if (elem.dataset.options) {
                let options = JSON.parse(elem.dataset.options);
                if (options.icons && options.icons[0] && options.icons[1]) {
                    elem.classList.toggle(options.icons[0]);
                    elem.classList.toggle(options.icons[1]);
                }
            }

            if (elem.dataset.targetId && document.querySelector('#' + elem.dataset.targetId) !== null) {
                let target = document.querySelector('#' + elem.dataset.targetId);
                target.classList.toggle('d-n');
            } else {
                elem.classList.toggle('d-n');
            }
        })
    }
}

new ToggleContent();