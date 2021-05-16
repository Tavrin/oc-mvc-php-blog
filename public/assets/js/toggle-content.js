"use strict";

class ToggleContent {
    constructor(elem) {
        this.state = true;
        this.type = null;
        this.options = [];
        this.toggleClass = 'd-n';
        this.revertToggle = false;
        this.target = elem;
        this.addListener(elem);
    }

    addListener(elem) {
        if (elem.dataset.options) {
            this.options = JSON.parse(elem.dataset.options);
        }

        if (elem.dataset.targetId && document.querySelector('#' + elem.dataset.targetId) !== null) {
            this.target = document.querySelector('#' + elem.dataset.targetId);
        }
        if (this.target.dataset.revertToggle && 'true' === this.target.dataset.revertToggle) {
            this.revertToggle = true;
        }
        if ('display' === elem.dataset.type) {
            this.type = 'display';
            this.setDisplayEvent(elem);
        }
    }

    setDisplayEvent(elem) {
        window.addEventListener('mouseup', (e) => {
            if (((this.target.classList.contains(this.toggleClass) && true === this.revertToggle) || (!this.target.classList.contains(this.toggleClass) && false === this.revertToggle)) && !elem.contains(e.target) && this.target.id !== 'editorjsChapo' && this.target.id !== 'editorjsContent') {
                this.displayEventChanges(elem);
            }
        })
        elem.addEventListener('click', () => {
            this.displayEventChanges(elem);
        })
    }

    displayEventChanges(elem) {
        this.state = !this.state;
        if (this.options['icons'] && this.options['icons'][0] && this.options['icons'][1]) {
            elem.classList.toggle(this.options['icons'][0]);
            elem.classList.toggle(this.options['icons'][1]);
        }

        if (this.target) {
            if (this.target.dataset.toggleClass) {
                this.toggleClass = this.target.dataset.toggleClass;
            }
            this.target.classList.toggle(this.toggleClass);
        } else {
            elem.classList.toggle(this.toggleClass);
        }
    }
}

document.querySelectorAll('.js-toggle').forEach((element) => {
   new ToggleContent(element);
})