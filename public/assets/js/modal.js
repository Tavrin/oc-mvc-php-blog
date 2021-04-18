"use strict";

class Modal {
    constructor(elem) {
        this.state = false;
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

        this.setModalEvent(elem)
    }

    setModalEvent(elem) {
        elem.addEventListener('click', () => {
            console.log(elem);
            this.state = !this.state;
            if (elem.dataset.targetModal && document.querySelector('#' + elem.dataset.targetModal) !== null) {
                let target = document.querySelector('#' + elem.dataset.targetModal);
                console.log(target);
                target.classList.toggle('d-n');
            }
        })
    }
}

document.querySelectorAll('.js-modal').forEach((element) => {
    new Modal(element);
})