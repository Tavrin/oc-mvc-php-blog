export default class MediaModal {
    constructor(mediaPicker) {
        this.mediaPicker = mediaPicker;
    }

    renderModal(wrapper) {
        this.wrapper = wrapper;
        this.wrapper.innerHTML = `
                <div class="modal-bg fade" id="mediaModalEditor">
            <div class="modal">
                <i class="fas fa-times fs-1c5 float-r" id="closeModalButton"></i>
                <h4 class="ta-c">Galerie Media</h4>
                <button class="m-0-5 button-bb-wc br-5 fw-600" id="addImageButton">Ajouter une image</button>
                <div id="gallery-view-editor" class="d-f fd-r flex-w grid-3 jc-fs mt-1 pb-3">
                    <p class="ta-c" id="ajaxStatus"> Chargement..</p>.
                </div>
            </div>
        </div>
        `;

        this.addCloseButton(this.wrapper);
        this.addModalData();
        return this.wrapper;

    }

    addCloseButton(elem) {
        let button = elem.querySelector('#closeModalButton');
        button.addEventListener('click', () => {
            this.wrapper.classList.toggle('d-n');
        })
    }

    addModalData() {
        let galleryView = this.wrapper.querySelector('#gallery-view-editor');
        this.ajaxCall('http://localhost:8001/api/medias/image').then(data => {
            if (this.wrapper.querySelector('#ajaxStatus')) {
                this.wrapper.querySelector('#ajaxStatus').style.display = 'none';
            }
            data.items.forEach(element => {
                let containerItem = document.createElement('div');
                containerItem.classList.add('maw-22', 'd-f', 'p-1', 'fd-c', 'ai-c', 'jc-sb');
                containerItem.innerHTML = `
                    <img src="${element['path']}" alt="" class="maw-100 of-cov">
                    <div>
                        <p>${element.name}</p>
                        <button class="button-bb-wc editor-modal-media-button" data-path="${element['path']}" >Sélectionner</button>
                    </div>
                `;

                galleryView.appendChild(containerItem);
            })

            document.querySelectorAll('.editor-modal-media-button').forEach((e) => {
                e.addEventListener('click', () => {this.mediaPicker._createMedia(e.dataset.path)})
            });
        })
    }

    ajaxCall(url, method = 'GET', body = null) {
        return fetch(url, {
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
                        statusText: response.statusText,
                        status: response.status
                    });
                }
            })
            .then((data) => {
                return data.response;
            })
            .catch(function (error) {
            });
    }
}