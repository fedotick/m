$(document).ready(function() {
    // Confirm deletion
    $('.delete-btn').click(function(e) {
        return confirm('Sunteți sigur că doriți să ștergeți această înregistrare?');
    });

    // Filter tables
    $('#filterPacient').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#pacientTable tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $('#filterMedic').on('change', function() {
        var value = $(this).val().toLowerCase();
        $('#medicTable tr').filter(function() {
            $(this).toggle($(this).find('td:eq(3)').text().toLowerCase().indexOf(value) > -1 || value === '');
        });
    });
});