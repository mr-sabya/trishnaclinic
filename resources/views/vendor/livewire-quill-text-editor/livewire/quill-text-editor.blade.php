<div wire:ignore>
    <div id="{{ $quillId }}"></div>
</div>

@script
<script>
    const toolbarOptions = [
        [{
            'font': []
        }],
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }],

        [{
            'size': ['small', false, 'large', 'huge']
        }], // custom dropdown

        ['bold', 'italic', 'underline'], // toggled buttons
        
        ['link', 'image'],

        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }, {
            'list': 'check'
        }],

        [{
            'color': []
        }, {
            'background': []
        }], // dropdown with defaults from theme

        [{
            'align': []
        }],

        ['blockquote', 'code-block'],

        ['clean'] // remove formatting button
    ];


    const quill = new Quill('#' + @js($quillId), {
        modules: {
            toolbar: toolbarOptions
        },
        theme: @js($theme)
    });

    quill.root.innerHTML = $wire.get('value');

    quill.on('text-change', function() {
        let value = quill.root.innerHTML;
        @this.set('value', value);
    });
</script>
@endscript