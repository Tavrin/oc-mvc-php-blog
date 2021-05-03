"use strict";

let utils = window.utils;

class HeaderNav {
    constructor() {
        this.burger = document.querySelector('#userDropdown');
        this.userLinks = document.querySelector('#userDropdownLinks');
        this.init();
    }

    init() {
        document.querySelectorAll('.flash').forEach(this.addFlash, this);
        this.toggleBurger();
    }

    addFlash(alert) {
        if (alert.querySelectorAll('.flash-close').length > 0) {
            let button = alert.querySelectorAll('.flash-close')[0];
            button.addEventListener('click', utils.addCloseEventOnParent);
        }
    }

    toggleBurger() {
        if (this.burger) {
            window.addEventListener('mouseup', (e) => {
                if (this.userLinks.classList.contains('active') && !this.burger.contains(e.target)) {
                    this.setBurgerVisuals();
                }
            })
            this.burger.addEventListener('click', () => {
                this.setBurgerVisuals();
            })
        }
    }

    setBurgerVisuals() {
        this.burger.children[0].classList.toggle('fa-bars');
        this.burger.children[0].classList.toggle('fa-times');
        this.userLinks.classList.toggle('active');
    }
}

new HeaderNav();
