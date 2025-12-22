<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>


<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            toolbar: [ 
                ['font', ['bold', 'underline', 'clear']],   
                ['para', ['ul', 'ol', 'paragraph']],
                ['alignment', ['left', 'center', 'right', 'justify']]
            ],
            height: 200
        });
 
        // Add 'btn-close' class to the '.close' element and set the icon
        $('.close').addClass('btn-close').html('&times;');

        // Click event to close the modal
        $('.close').click(function() {
            $('.note-modal').hide(); // Or use .fadeOut() for a fade effect
            $('.modal-backdrop').hide(); // Or use .fadeOut() for a fade effect
        });
    });
</script>
