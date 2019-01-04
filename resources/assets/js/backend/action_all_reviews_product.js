const selectAllReviews_label = $('.slc-all-reviews-prod-text');
const advanceSelectAllReviews = $('#ckc-all-reviews-prod');
const table = $('.table-prod-review, #table-select-all');
const table_checkbox = $('[name="listReviewCheck[]"]');
const selectAllReviews = $('#review-approve-select-all');
const totalReviewsProduct = totalReviewsProductCurrent = $('[name="total-all-reviews-prod"]').val();
const productUncheckCurrentPageInput = $('#product-uncheck-current-page');
let productUncheckCurrentPage = [];

/*
    Action select all reviews a page
*/
$("#review-approve-select-all").on("change", function(ev) {
    if( $(this).is(':checked') ) {
        // checked
        updateTextTotal($(table_checkbox).length, totalReviewsProduct);
        $(".status-action-wrap").show();
        $('#slc_all_reviews_prod_wrap') && $('#slc_all_reviews_prod_wrap').show();
        $(table_checkbox).prop("checked", true);
        $(table_checkbox).closest("tr").addClass("row-selected");

        if( totalReviewsProduct > 10 ) {
            $(selectAllReviews_label).show();
        }
    } else {
        // uncheck
        turnOffSelectAll();
    }   
});

/*
    Action select all reviews all pages
*/
$('#ckc-all-reviews-prod').on('change', function() {
    if( $(advanceSelectAllReviews).is(':checked') ) {
        // checked
        updateTextTotal(totalReviewsProduct, totalReviewsProduct);
        $(selectAllReviews_label).hide();
        $(selectAllReviews).prop('checked', true);
        $(table).find(table_checkbox).each(function(index) {
            $(this).prop('checked', true);
            $(this).closest('tr').addClass('row-selected');
        })
    }
});

/*
    Change check each review of product
*/
$(table_checkbox).on('change', function() {
    // 
    if( $(this).is(':checked') ) {
        $(this).prop('checked', true);
        $(this).closest('tr').addClass('row-selected');

        $(".status-action-wrap").show();

        $(advanceSelectAllReviews).is(':checked') ? totalReviewsProductCurrent++ : totalReviewsProduct;

        // Checked: Remove a data-product-id in variable productUncheckCurrentPage
        productUncheckCurrentPage.splice(productUncheckCurrentPage.indexOf($(this).val()), 1);
    } else {
        $(this).prop('checked', false);
        $(this).closest('tr').removeClass('row-selected');

        $(advanceSelectAllReviews).is(':checked') ? totalReviewsProductCurrent-- : totalReviewsProduct;

        // Uncheck: Add a data-product-id in variable productUncheckCurrentPage
        productUncheckCurrentPage.push($(this).val());

        if( !$(table_checkbox).is(':checked') && !$(advanceSelectAllReviews).is(':checked') ) {
            $(".status-action-wrap").hide();
            $(selectAllReviews_label).hide();
        }

        if( !isTableCheckedALL() && !$(advanceSelectAllReviews).is(':checked')) {
            $(selectAllReviews).prop('checked', false);
        }
    }

    $(productUncheckCurrentPageInput).val(productUncheckCurrentPage.join(','));

    // Update text 
    if ( $(advanceSelectAllReviews).is(':checked') ) {
        updateTextTotal(totalReviewsProductCurrent, totalReviewsProduct);
    } else {
        var countChecked = 0;
        $(table_checkbox).each(function() {
            if ( $(this).is(':checked') ) {
                countChecked++;
            }
        });

        updateTextTotal(countChecked, totalReviewsProduct);
    }
});

function turnOffSelectAll() {
    $('#result-total-reviews-checked').html(`<strong id="result-total-reviews-checked"><div class="number-reviews">0</div>  of <div class="number-reviews"> ${ totalReviewsProduct } </div> review(s)</strong>`);
    $(".status-action-wrap").hide();
    $(selectAllReviews_label).hide();
    $(table_checkbox).prop("checked", false);
    $(table_checkbox).closest("tr").removeClass("row-selected");
    $(advanceSelectAllReviews).prop('checked', false);
    $(selectAllReviews).prop('checked', false);

    // Reset variable
    totalReviewsProductCurrent = $('[name="total-all-reviews-prod"]').val();
    $(productUncheckCurrentPageInput).val('');
    productUncheckCurrentPage = [];
}

function isTableCheckedALL() {
    let isCheckedAll = true;
    $(table_checkbox).each(function() {
        if( !$(this).is(':checked') ) {
            isCheckedAll = false;
        }
    });

    return isCheckedAll;
}

function updateTextTotal(current, total) {  
    var strText = '<strong id="result-total-reviews-checked"><div class="number-reviews">' + current + '</div>  of <div class="number-reviews">' + total + '</div> review(s)</strong>';
    $('#result-total-reviews-checked').html(strText);
}