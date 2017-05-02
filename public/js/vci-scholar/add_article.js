// deprecate at 05/04/2017
// Nhập thẳng organizes không cần search
// "use strict";
// var $count_organize = 0;
// $("#add_more_author").click(function () {
//     $count_organize = $count_organize + 1;
//     var html_author_div = "<div class='row margin-bottom-10'> <div class='col-md-4'> <input type='hidden' name='authors_id[]' value='-1'><input type='text' name='names[]' class='form-control' placeholder='Họ tên'> </div> <div class='col-md-3'> <input type='text' class='form-control' name='emails[]' placeholder='Email'> </div> <div class='col-md-3'> <select class='form-control organize-select' name='organizes[]' id='organize_count_" + $count_organize + "'><option value='-1'</option></select> </div> <div class='col-md-2 pull-right'> <input type='hidden' name='create_authors[]' value='1'> <a href='javascript:void(0)' class='btn red author-remove'><i class='glyphicon glyphicon-remove'></i></a> </div> </div>";
//     $(".authors-body").append(html_author_div);
//     $("#organize_count_" + $count_organize).select2({
//         ajax: {
//             url: $pathWebsite + "/api/organizes/search",
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     q: params.term // search term
//                 };
//             },
//             processResults: function (data) {
//                 return {
//                     results: data.data,
//                 };
//             },
//             cache: true
//         },
//         escapeMarkup: function (markup) {
//             return markup;
//         }, // let our custom formatter work
//         minimumInputLength: 1,
//         templateResult: formatOrganize, // omitted for brevity, see the source of this page
//         templateSelection: formatOrganizeSelection // omitted for brevity, see the source of this page
//     });
//
// });
//
// $(document).delegate(".author-remove", "click", function () {
//     $(this).parent().parent().remove();
// });


"use strict";
var $count_organize = 0;
$("#add_more_author").click(function () {
    $count_organize = $count_organize + 1;
    var html_author_div = "<div class='row margin-bottom-10'> <div class='col-md-4'> <input type='hidden' name='authors_id[]' value='-1'><input type='text' name='names[]' class='form-control' placeholder='Họ tên'> </div> <div class='col-md-3'> <input type='text' class='form-control' name='emails[]' placeholder='Email'> </div> <div class='col-md-3'> <input type='text' class='form-control' name='organizes_name[]' value=' ' /> </div> <div class='col-md-2 pull-right'> <input type='hidden' name='create_authors[]' value='1'> <a href='javascript:void(0)' class='btn red author-remove'><i class='glyphicon glyphicon-remove'></i></a> </div> </div>";
    $(".authors-body").append(html_author_div);


});

$(document).delegate(".author-remove", "click", function () {
    $(this).parent().parent().remove();
});
