"use strict";

export class FormRender {
    constructor() {
        this.form =  document.createElement('form');
    }

    renderFromDefault(data) {
        let obj = Object.entries(data['fields']);
        this.form.setAttribute('action', data['action']);
        obj.forEach((item) => {
            let input = null
            switch (item[1]['type']) {
                case 'text'   :
                case 'hidden' :
                case 'file'   :
                    input = this.renderTextInput(item)
                    break;
                case 'datetime' :
                    input = this.renderDatetime(item)
                    break
            }
            if (input) {
                this.form.appendChild(input);
            }
        })

        this.form.classList.add('d-f', 'fd-c', 'ai-c')
        let button = document.createElement('button');
        button.type = 'button';
        button.id = 'renderNewMediaButton';
        button.classList.add('m-0-5', 'button-bb-wc', 'br-5','fw-600');
        button.textContent = 'Valider';
        this.form.appendChild(button);
        return this.form;
    }

    renderTextInput(data) {
        let input = document.createElement('input');
        let wrapper = document.createElement('div');
        wrapper.classList.add('mb-1','d-f','fd-c','ai-c')
        input.type = data[1]['type'];
        DOMTokenList.prototype.add.apply(input.classList, data[1]['class']);
        input.classList.add('w-80');
        input.setAttribute('name', data[0]);
        if (data[1]['required']) {
            input.setAttribute('required', '');
        }
        if (data[1]['value']) {
            input.setAttribute('value', data[1]['value']);
        }
        input.setAttribute('placeholder', data[1]['placeholder']);
        wrapper.appendChild(input);
        wrapper.classList.add('w-80');
        return wrapper;
    }

    renderDatetime(data) {
        let input = document.createElement('input');
        input.type = 'datetime-local';
        if (data[1]['required']) {
            input.setAttribute('required', '');
        }
        if (data[1]['value']) {
            input.setAttribute('value', data[1]['value']);
        }

        let wrapper = document.createElement('div');
        wrapper.classList.add('mb-1','d-f','fd-c','ai-c')
        DOMTokenList.prototype.add.apply(input.classList, data[1]['class']);
        input.classList.add('w-50');
        input.setAttribute('placeholder', data[1]['placeholder']);
        input.setAttribute('name', data[0]);
        wrapper.appendChild(input);
        wrapper.classList.add('w-80');
        return wrapper;
    }
}