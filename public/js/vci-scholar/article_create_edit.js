$.fn.select2.defaults.set("theme", "bootstrap");

var placeholder = "Chọn tạp chí";

$("#journal_id").select2({
    placeholder: placeholder,
    ajax: {
        url: $api_journal_search,
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term ? params.term : "" // search term
            };
        },
        processResults: function (data) {
            return {
                results: data.data
            };
        },
        cache: true
    },
    escapeMarkup: function (markup) {
        return markup;
    }, // let our custom formatter work
    templateResult: formatJournal, // omitted for brevity, see the source of this page
    templateSelection: formatJournalSelection // omitted for brevity, see the source of this page
});

$('label[for="journal_id"]').click(function() {
    $('#journal_id').select2("open");
});

function formatJournal(journal) {
    if (journal.loading) return journal.text;

    var $markup = "";
    if (journal) {
        if (journal.description) {
            $markup = "<div>" + journal.name + " | " + journal.description + "</div>";
        } else {
            // address null
            $markup = "<div>" + journal.name + "</div>";
        }
    }
    return $markup;
}

function formatJournalSelection(journal) {

    if (journal && journal.name) {
        return journal.name;
    }
    return journal.text;
}

var new_author_element = $('#new_author_html');
var new_author_html = new_author_element.html();
new_author_element.remove();
var authorArray = new Array2d([
    {
        name: 'authors',
        attributes: ['name', 'email', 'organize_name']
    }
]);

function addAuthor() {
    var target = $('#add-author-before-me');
    target.before(new_author_html);
    target.prev().slideDown("fast");
    target.prev().find('input[name="names[]"]').focus();
    initRemoveBtn();
    authorArray.update();
}

function initRemoveBtn() {
    $('.author-remove').click(function() {
        var target = $(this).parent().parent();
        target.slideUp("fast", function() {
            target.remove();
            authorArray.update();
        });
    })
}

$('#form').submit(function() {
    bootbox.dialog({
        message: '<p><i class="fa fa-spin fa-spinner"></i> Đang xử lý...</p>'
    });
});

initRemoveBtn();
authorArray.update();