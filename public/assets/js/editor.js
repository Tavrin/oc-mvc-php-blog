let editors = document.querySelectorAll('.editor');
editors.forEach((el) => {
    console.log(el.id);

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

            /**
             * Or pass class directly without any configuration
             */
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
        data: {},
        onReady: function(){
            saveButton.click();
        },
        onChange: function() {
            console.log('something changed');
        }
    });

    const saveButton = document.getElementById(el.dataset.button);

    saveButton.addEventListener('click', function () {
        editor.save()
            .then((outputData) => {
            console.log('Article data: ', outputData)
        }).catch((error) => {
            console.log('Saving failed: ', error)
        });
    });
});

