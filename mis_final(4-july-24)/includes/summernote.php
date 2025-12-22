<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<!-- include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- include Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- jQuery v3.7.1 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // $(document).ready(function() {
    //     $('.summernote').summernote();
    // });

    $(document).ready(function() {
      $('.summernote').summernote({
        height: 200 // set the height in pixels
      });
    });
</script>


<!-- <script>
    $(document).ready(function() {
        $('.summernote').summernote({
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']]
            ],
        });
    });
</script> -->
<!-- summernote js end  -->