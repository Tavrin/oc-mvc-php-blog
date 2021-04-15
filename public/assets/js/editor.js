let editors = document.querySelectorAll('.editor');
let saveCount = 0;
editors.forEach((el) => {
    let targetForm = null;
    let targetInput = null;
    let content = null;
    if (el.dataset.content) {
        content = JSON.parse(el.dataset.content);
    }

    const saveButton = document.getElementById(el.dataset.button);

    if (saveButton.dataset.target) {
        document.querySelector(`#${saveButton.dataset.target}`) ? targetForm = document.querySelector(`#${saveButton.dataset.target}`): targetForm = null;
    }

    if (el.dataset.target) {
        document.querySelector(`#${el.dataset.target}`) ? targetInput = document.querySelector(`#${el.dataset.target}`) : targetInput = null;
    }

    const editor = new EditorJS({
        readOnly: false,
        holder: el.id,
        placeholder: 'Nouvel article',
        tools: {
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
            },
            header: {
                class: Header,
                inlineToolbar: ['marker', 'link'],
                config: {
                    placeholder: 'Header'
                },
                shortcut: 'CMD+SHIFT+H'
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
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+L'
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
                shortcut: 'CMD+SHIFT+O'
            },

            warning: Warning,

            marker: {
                class: Marker,
                shortcut: 'CMD+SHIFT+M'
            },

            code: {
                class: CodeTool,
                shortcut: 'CMD+SHIFT+C'
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

        },

        data: content
    });


    saveButton.addEventListener('click', () => {
        editor.save()
            .then((outputData) => {
                outputData.id = el.id;
                targetInput.value = JSON.stringify(outputData);
                targetForm.requestSubmit();
            }).catch((error) => {
                console.log('Saving failed: ', error);
            });
    });
});

