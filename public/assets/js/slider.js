"use strict";

let utils = window.utils;

class Slider {
    constructor(elem) {
        this.previousButton = elem.querySelector('.slider-previous');
        this.nextButton = elem.querySelector('.slider-next');
        this.items =  elem.querySelectorAll('.featured-item');
        this.count = this.items.length;
        this.currentItem = elem.querySelector('.featured-item.active');
        this.previousItem = null;
        this.nextItem = null;
        this.dots = null;
        if (elem.dataset.type && elem.dataset.type === 'dotted') {
            this.dots = elem.querySelectorAll('.featured-dot');
        }
        this.addEvents();
        this.setOrder();
    }

    addEvents() {
        this.previousButton.addEventListener('click', (e) => {this.slideEvent(e, 'previous')});
        this.nextButton.addEventListener('click', (e) => {this.slideEvent(e, 'next')});
    }

    slideEvent(e, direction) {
        e.preventDefault();
        this.currentItem.classList.remove('active');
        if ('previous' === direction) {
            this.previousItem.classList.add('active');
            if (this.dots) {
                this.dots[parseInt(this.currentItem.dataset.order)].innerHTML = '<i class="far fa-circle">';
                this.dots[parseInt(this.previousItem.dataset.order)].innerHTML = '<i class="fas fa-circle">';
            }
            this.currentItem = this.previousItem;
        }
        if ('next' === direction) {
            this.nextItem.classList.add('active');
            if (this.dots) {
                this.dots[parseInt(this.nextItem.dataset.order)].innerHTML = '<i class="fas fa-circle">';
                this.dots[parseInt(this.currentItem.dataset.order)].innerHTML = '<i class="far fa-circle">';
            }
            this.currentItem = this.nextItem;
        }

        this.setOrder();
    }

    setOrder() {
        if (parseInt(this.currentItem.dataset.order) === 0) {
            this.previousItem = this.items[this.count - 1];
        } else {
            this.previousItem = this.items[parseInt(this.currentItem.dataset.order) - 1];
        }

        if (parseInt(this.currentItem.dataset.order) === this.count - 1) {
            this.nextItem = this.items[0];
        } else {
            this.nextItem = this.items[parseInt(this.currentItem.dataset.order) + 1];
        }
    }
}

document.querySelectorAll('.js-slider').forEach((element) => {
    new Slider(element);
})