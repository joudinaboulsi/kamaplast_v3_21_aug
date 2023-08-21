<?php use App\Http\Controllers\Frontend\ProductsController; ?>


@foreach($show_list as $product)

	<div class="item col-sm-4 col-md-4 col-lg-3 col-xs-6">
		<?php ProductsController::displayProductElement($product); ?>
	</div>
@endforeach


<script type="text/javascript">

var interval = <?=json_encode($interval) ?>; 
$('#min_price').val(interval.min_price); // set the min price of the price range filter
$('#max_price').val(interval.max_price); // set the max price of the price range filter
$("#product_nb").html($('.product').length); // count the number of products in the displayed page

</script>