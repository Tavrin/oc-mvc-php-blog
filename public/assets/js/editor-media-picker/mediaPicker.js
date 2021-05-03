import MediaModal from './mediaModal.js';
export default class MediaPicker {
    constructor({data}){
        this.data = data;
        this.wrapper = null;
        this.mediaModal = new MediaModal(this);
    }

    static get toolbox() {
        return {
            title: 'Mediapicker',
            icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>'
        };
    }

    render() {
        this.wrapper = document.createElement('div');

        if (this.data && this.data.url){
            this._createMedia(this.data.url, this.data.caption);
            return this.wrapper;
        }

        this.wrapper = this.mediaModal.renderModal(this.wrapper)
        return this.wrapper;
    }

    save(blockContent){
        const image = blockContent.querySelector('img');
        const caption = blockContent.querySelector('input');

        return {
            url: image.src,
            caption: caption.value,
            mediaType: 'image'
        }
    }

    _createMedia(path, captionText) {
        const image = document.createElement('img');
        const caption = document.createElement('input');
        image.src = path;
        image.style.maxWidth = '100%';
        caption.placeholder = 'Légende...';
        caption.classList.add('form-control');
        caption.value = captionText || '';
        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(image);
        this.wrapper.appendChild(caption);

    }
}