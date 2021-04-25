"use strict";
import {formRender} from './formRender.js';
let utils = window.utils;


class Modal {
    constructor(elem) {
        this.button = document.querySelector('#closeModalButton');
        this.initiated = false;
        this.state = false;
        this.type = null;
        this.options = [];
        this.galleryView = null;
        this.formView = null;
        this.open = {
            "form" : false
        };
        this.addCloseButton();
        this.addListener(elem);
        this.target = elem;
        this.loaded = {
            'gallery' : false,
            'form' : false
        };
    }

    addCloseButton() {
        this.button.addEventListener('click', () => {
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
                if (false === this.initiated) {
                    this.setModalData(elem);
                }
            }
        })
    }

     setModalData(elem) {
         this.galleryView = this.target.querySelector('#gallery-view');
         this.formView = this.target.querySelector('#form-view');
         let newImage = this.target.querySelector('#addImageButton');
         newImage.addEventListener('click', (e) => {this.modalButtonAddImage(e)})
         this.addMediaToGallery();
         this.initiated = true;
    }

    addMediaToGallery() {
        if (false === this.loaded.gallery) {
            this.ajaxCall('/api/medias/image').then(data => {
                if (this.target.querySelector('#ajaxStatus')) {
                    document.querySelector('#ajaxStatus').style.display = 'none';
                }
                data.items.forEach(element => {
                    let containerItem = document.createElement('div');
                    let imageInfo = document.createElement('div');
                    imageInfo.innerHTML = `
                        <p>${element.name}</p>
                        <button class="button-bb-wc modal-media-button" data-path="${element.path}" >Sélectionner</button>
                        `
                    containerItem.classList = 'maw-22 d-f p-1 fd-c ai-c jc-sb';
                    let item = document.createElement('img');
                    item.src = element['path'];
                    item.classList = 'maw-100 of-cov';
                    containerItem.appendChild(item);
                    containerItem.appendChild(imageInfo);
                    this.galleryView.appendChild(containerItem);
                })

                document.querySelectorAll('.modal-media-button').forEach((e) => {
                    e.addEventListener('click', (e) => {this.modalButtonGetMedias(e)})
                });
                this.loaded.gallery = true;
            });
        }
    }

    modalButtonAddImage(e = null) {
        if (!this.open.form) {
            e.currentTarget.textContent = 'Retour';
        } else if (e) {
            e.currentTarget.textContent = 'Ajouter une image';
        }

        this.galleryView.classList.toggle('d-n');
        if (false === this.loaded.form) {
            this.ajaxCall('/api/medias/image/new').then(data => {
                if (this.target.querySelector('#ajaxStatus')) {
                    document.querySelector('#ajaxStatusNew').style.display = 'none';
                }
                this.loaded.form = true;
                let renderer = new formRender();
                let form = renderer.renderFromDefault(data.data);
                form.querySelector('#renderNewMediaButton').addEventListener('click', (e) => {this.createNewMedia(e, form)})
                this.formView.appendChild(form);
            });
        }

        this.formView.classList.toggle('d-n');
        if (true === this.open.form) {
            this.addMediaToGallery();
        }
        this.open.form = !this.open.form ;
    }

    createNewMedia(e, form) {
        const formData = new FormData(form);
        this.ajaxCall('/api/medias/image/new', 'POST', formData).then(data => {
            if ('ok' === data) {
                this.loaded.gallery = false;
                this.galleryView.innerHTML = '';
                let button = this.target.querySelector('#addImageButton');
                let event = new Event('click');
                button.dispatchEvent(event);
            }
        });
    }

    modalButtonGetMedias(e) {
        let inputTarget = document.querySelector('#mediaHiddenInput');
        inputTarget.value = e.currentTarget.dataset.path;
        let event = new Event('change');
        inputTarget.dispatchEvent(event);
    }

    ajaxCall(link, method = 'GET', body = null) {
        return fetch(link, {
            method: method,
            body: body,
            headers : {
                Accept: "*/*"
            }
        })
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