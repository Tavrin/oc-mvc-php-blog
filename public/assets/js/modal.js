"use strict";

let utils = window.utils;


class Modal {
    constructor(elem) {
        this.button = document.querySelector('#closeModalButton');
        this.state = false;
        this.type = null;
        this.options = [];
        this.addCloseButton();
        this.addListener(elem);
        this.target = elem;
        this.loaded = false;
    }

    addCloseButton() {
        this.button.addEventListener('click', () => {
            console.log('clicked');
            this.target.classList.toggle('d-n');
        })
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
            this.state = !this.state;
            if (elem.dataset.targetModal && document.querySelector('#' + elem.dataset.targetModal) !== null) {
                this.target = document.querySelector('#' + elem.dataset.targetModal);
                this.target.classList.toggle('d-n');
                this.setModalData(elem);
            }
        })
    }

     setModalData(elem) {
        let galleryView = this.target.querySelector('#gallery-view');

         if (!this.loaded) {
             this.ajaxCall('/api/medias/image').then(data => {
                 if (this.target.querySelector('#ajaxStatus')) {
                     document.querySelector('#ajaxStatus').style.display = 'none';
                 }
                 data.items.forEach(element => {
                     console.log(element);
                     let containerItem = document.createElement('div');
                     let imageInfo = document.createElement('div');
                     imageInfo.innerHTML = `
                        <p>${element.name}</p>
                        <button class="button-bb-wc modal-media-button" data-path="${element.path}" >Sélectionner</button>
                        `
                     containerItem.classList = 'maw-25 d-f p-1 fd-c ai-c jc-sb';
                     let item = document.createElement('img');
                     item.src = element['path'];
                     item.classList = 'maw-100 of-cov';
                     containerItem.appendChild(item);
                     containerItem.appendChild(imageInfo);
                     galleryView.appendChild(containerItem);
                 })

                 document.querySelectorAll('.modal-media-button').forEach((e) => {
                     e.addEventListener('click', (e) => {this.modalButtonListener(e)})
                 });
                 this.loaded = true;
             });
         }

    }

    modalButtonListener(e) {
        let inputTarget = document.querySelector('#mediaHiddenInput');
        inputTarget.value = e.currentTarget.dataset.path;
        let event = new Event('change');
        inputTarget.dispatchEvent(event);
        console.log(this.target.classList.toggle('d-n'));
    }

    ajaxCall(link) {
        return fetch(link)
            .then((response) => {
                if (response.ok) {
                    return response.json();
                } else {
                    return Promise.reject({
                        status: response.status,
                        statusText: response.statusText
                    });
                }
            })
            .then((data) => {
                console.log('success');
                return data.response;
            })
            .catch(function (error) {
                console.log('error', error);
            });
    }
}

document.querySelectorAll('.js-modal').forEach((element) => {
    new Modal(element);
})