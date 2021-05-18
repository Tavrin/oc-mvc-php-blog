import MediaPicker from "./editor-media-picker/mediaPicker.js";

let editors = document.querySelectorAll('.editor');
let saveCount = {
    'totalEditors' : 0,
    'currentSaved' : 0
};

window.targetForm = null;

editors.forEach((el) => {
    saveCount['totalEditors']++;
    let targetForm = null;
    let targetInput = null;
    let content = null;
    if (el.dataset.content) {
        content = JSON.parse(el.dataset.content);
    }

    const saveButton = document.getElementById(el.dataset.button);

    if (saveButton.dataset.target) {
        document.querySelector(`#${saveButton.dataset.target}`) ? window.targetForm = document.querySelector(`#${saveButton.dataset.target}`): window.targetForm = null;
    }

    if (el.dataset.target) {
        document.querySelector(`#${el.dataset.target}`) ? targetInput = document.querySelector(`#${el.dataset.target}`) : targetInput = null;
    }

    const editor = new EditorJS({
        holder: el.id,
        placeholder: 'Nouvel article',
        tools: {
            mediaPicker: MediaPicker,
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
            },
            header: {
                class: Header,
                tunes: ['AlignmentBlockTune'],
                inlineToolbar: ['marker', 'link'],
                config: {
                    placeholder: 'Header'
                },
                shortcut: 'CTRL+SHIFT+H'
            },
            AnyButton: {
                class: AnyButton,
                inlineToolbar: false,
                config:{
                    css:{
                        "btnColor": "btn--gray",
                    }
                }
            },

            image: SimpleImage,


            list: {
                class: List,
                inlineToolbar: false
            },

            checklist: {
                class: Checklist,
                inlineToolbar: true,
            },

            quote: {
                class: Quote,
                inlineToolbar: true,
                config: {
                    quotePlaceholder: 'Enter a quote',
                    captionPlaceholder: 'Quote\'s author',
                },
                shortcut: 'CTRL+SHIFT+O'
            },

            warning: Warning,

            marker: {
                class: Marker,
                shortcut: 'CTRL+SHIFT+M'
            },

            code: {
                class: CodeTool,
                shortcut: 'CTRL+SHIFT+C'
            },

            delimiter: Delimiter,

            inlineCode: {
                class: InlineCode,
                shortcut: 'CTRL+SHIFT+C'
            },

            linkTool: LinkTool,

            embed: Embed,

            table: {
                class: Table,
                inlineToolbar: true,
                shortcut: 'CMD+ALT+T'
            },
            AlignmentBlockTune: {
                class: AlignmentBlockTune,
            }
        },

        data: content
    });


    saveButton.addEventListener('click', () => {
        editor.save()
            .then((outputData) => {
                outputData.id = el.id;
                console.log(outputData);
                targetInput.value = JSON.stringify(outputData);
                saveCount['currentSaved']++;
                submitForm();
            }).catch((error) => {
                console.log('Saving failed: ', error);
            });
    });
});

let submitForm = () => {
    if (saveCount['totalEditors'] === saveCount['currentSaved']) {
        targetForm.requestSubmit();
    }
}


