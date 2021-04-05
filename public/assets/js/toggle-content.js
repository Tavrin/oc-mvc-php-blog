"use strict";

class ToggleContent {
    constructor(elem) {
        this.state = true;
        this.type = null;
        this.options = [];
        this.addListener(elem);
        this.target = elem;
    }

    addListener(elem) {
        if (elem.dataset.options) {
            this.options = JSON.parse(elem.dataset.options);
        }

        if (elem.dataset.targetId) {
            this.target = `#${elem.dataset.targetId}`;
        }

        if ('display' === elem.dataset.type) {
            this.type = 'display';
            this.setDisplayEvent(elem);
        }
    }

    setDisplayEvent(elem) {
        elem.addEventListener('click', ()=> {
            if (this.options['icons'] && this.options['icons'][0] && this.options['icons'][1]) {
                elem.classList.toggle(this.options['icons'][0]);
                elem.classList.toggle(this.options['icons'][1]);
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

document.querySelectorAll('.js-toggle').forEach((element) => {
   new ToggleContent(element);
})