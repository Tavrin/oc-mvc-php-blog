"use strict";

let utils = window.utils;

class Binder {
    constructor(elem) {
        elem.value ? this.value = elem.value : this.value = null;
        elem.dataset.target ? this.target = elem.dataset.target : this.target = null;
        this.attribute = elem.dataset.targetAttribute;
        this.type = elem.dataset.type;
        elem.dataset.options ? this.options = JSON.parse(elem.dataset.options) : this.options = null;
        this.addListener(elem);
    }

    addListener(elem) {
        if ('text' === this.type || !this.type) {
            this.setTextEvent(elem);
        }

        if ('image' === this.type || !this.type) {
            this.setImageEvent(elem);
        }
    }

    setTextEvent(elem) {
        elem.addEventListener('keyup', () => {
            if (this.target && 'string' === typeof this.target) {
                console.log(this.target);
                this.target = document.querySelector(`#${this.target}`);
            }

            this.value = elem.value;
            if (this.options['slugify']) {
                this.value = utils.slugify(this.value);
            }
            this.target.value = this.value;
        })
    }

    setImageEvent(elem) {
        console.log('test');
        console.log(elem);
        elem.addEventListener('change', (e) => {
            console.log('test change');
            console.log(e.currentTarget);
            if (this.target && 'string' === typeof this.target) {
                this.target = document.querySelector(`#${this.target}`);
                console.log(this.target);
            }

            if (elem.dataset.from === 'file') {
                let file    = elem.files[0];
                let reader  = new FileReader();
                reader.onloadend = () => {
                    if (this.target.classList.contains('d-n')) {
                        this.target.classList.remove('d-n');
                    }
                    this.target.src = reader.result;
                }

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    this.target.src = "";
                }
            } else if(elem.dataset.from === 'modal') {
                this.target.src = elem.value;
                this.target.classList.remove('d-n');
            }

        })
    }
}

document.querySelectorAll('.js-binder').forEach((element) => {
    new Binder(element);
})