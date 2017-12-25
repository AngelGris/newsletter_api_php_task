$(function() {
    $('.btn-delete').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
});