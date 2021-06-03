import $ from  "jquery";
/*cart shopping*/

let count = 0;

// if add to cart btn clicked

$('.addCart').on('click', () => {

    setTimeout(function(){
        count++;
        $(".cart-add .d-flex").text(count);
    }, 1000);

})