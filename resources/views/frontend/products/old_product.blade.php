<?php use App\Http\Controllers\Frontend\ProductsController; ?>

@extends('frontend.layouts.app')

@section('content')

<!-- Product Details  -->
<!-- Getting today's date -->
<?php $now = date('Y-m-d'); ?>

<input type="hidden" value="{{ csrf_token() }}" name="_token">

<div class="white-bg" style="padding-bottom: 50px;">
    <div class="container main-container headerOffset">

        <div class="row transitionfx">

            <div class="col-xs-12 visible-xs" style="margin-bottom: 15px">
                <!-- Product Name -->
                <h1 class="product-title"> {{ $product[0]->name }}</h1>
                <!-- Product Brand -->
                <span class="new-product"> {{ $product[0]->brand }}</span>
            </div>

            <!-- LEFT COLUMN with photos of the product-->
            <div class="col-lg-6 col-md-6 col-sm-6">

                <!-- product Image and Zoom -->
                <div class="main-image main-image-thumb-list sp-wrap col-lg-12 no-padding style3 sp-non-touch"
                    style="display: inline-block;">
                    <!-- product Image and Zoom -->
                    <div class="main-image col-lg-12 no-padding">

                        <a href="{{ getenv('S3_URL') }}/products/large/{{ $product[0]->primary_img }}">
                            <img src="{{ getenv('S3_URL') }}/products/{{ $product[0]->primary_img }}"
                                class="img-responsive" alt="img">
                        </a>

                        @foreach ($images as $i)
                        @if ($i->is_primary != 1)
                        <a href="{{ getenv('S3_URL') }}/products/large/{{ $i->img }}">
                            <img src="{{ getenv('S3_URL') }}/products/{{ $i->img }}" class="img-responsive" alt="img">
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <!--/ left column end -->


            <!-- RIGHT COLUMN -->
            <div class="col-lg-6 col-md-6 col-sm-5">
                <!-- General Information of the selected product -->
                <!-- Product Name -->
                <h1 class="product-title hidden-xs"> {{ $product[0]->name }}</h1>

                <p class="lead text-info"> Please select product color and desired quantity before add to cart.</p>

                @if ($product[0]->brand != null)
                <!-- Product Brand -->
                <h3 class="product-code hidden-xs"><b>Brand:</b> {{ $product[0]->brand }}</h3>
                @endif

                <!-- Product code -->
                <h3 class="product-code hidden-xs"><b>Product code:</b> {{ $product[0]->sku }}</h3>

                <h3 class="product-code"><b>Dimensions:</b></h3>
                <img style="float:left; width:35px;" src="../images/dimension-icon.png" title="Dimensions" />
                <div style="height:23px; margin-left:50px; margin-top:16px;">
                    @if ($product[0]->length !== null)
                    <span title="Length">{{ $product[0]->length }}cm</span>
                    @endif
                    @if ($product[0]->width !== null and $product[0]->length !== null)
                    <span> x </span>
                    @endif
                    @if ($product[0]->width !== null)
                    <span title="Width">{{ $product[0]->width }}cm</span>
                    @endif
                    @if ($product[0]->height !== null and $product[0]->width !== null)
                    <span> x </span>
                    @endif
                    @if ($product[0]->height !== null)
                    <span title="Height">{{ $product[0]->height }}cm</span>
                    @endif
                </div>

                @if ($product[0]->diameter !== null)
                <div>
                    <img style="float:left; width:25px;" src="../images/diameter-icon.png" title="Diameter" />
                    <div style="height:23px; margin-left:50px; margin-top:16px;">
                        <span title="Diameter">{{ $product[0]->diameter }}cm </span>
                    </div>
                </div>
                @endif

                <br /><br />
                <!-- Product Short Description -->
                <div class="details-description contentBox">
                    <p>
                        <?php echo htmlspecialchars_decode($product[0]->short_description); ?>
                    </p>
                </div>

                <div class="row contentBox">
                    <div class="col-sm-12 col-xs-7">
                        <div class="price" style="font-size:28px; color:#4bb777">
                            @if ($actual_price != '')
                            <span class="pricetochange actual_price">{{ $actual_price }}</span>
                            <span class="price-standard old_price">{{ $old_price }}</span>
                            <span class="pricetochange" style="font-size: 20px">tax included</span>
                            @else
                            <span class="pricetochange actual_price">{{ $actual_price }}</span>
                            <span class="pricetochange" style="font-size: 20px">tax included</span>
                            <span class="price-standard old_price"></span>
                            @endif
                        </div>
                    </div>

                    <div class="visible-xs col-xs-5 f-right">
                        <!-- If there is at least 1 attribute associated to the product, the stock status of the variant
                                                        will be loaded dynamically from the Javascript in the in_stock class -->
                        @if (count($attributes) != 0)
                        <h3 class="incaps no-margin" id="in_stock"></h3>
                        <!-- If no attribute is linked to the product, the stock status will be the one of the unique variant associated to the product -->
                        @else
                        <!-- If Stock management is active (counting stock) -->
                        @if ($product[0]->enable_stock_mgmt == 1)
                        <!-- If no stock quantity -->
                        @if ($variants[0]->stock_qty == 0 or $variants[0]->stock_qty == null)
                        <h3 class="incaps no-margin" id="in_stock"><i class="fa fa fa-times-circle color-out"></i> Out
                            of stock</h3>
                        <!-- If stock available -->
                        @else
                        <h3 class="incaps no-margin" id="in_stock"><i class="fa fa fa-check-circle-o color-in"></i> In
                            stock
                            ({{ $variants[0]->stock_qty }} items remaining)</h3>
                        @endif
                        <!-- If stock management inactive (not counting stock) -->
                        @else
                        <!-- If out of stock -->
                        @if ($variants[0]->stock_status_id == 0 or $variants[0]->stock_status_id == null)
                        <h3 class="incaps no-margin" id="in_stock"><i class="fa fa fa-times-circle color-out"></i> Out
                            of stock</h3>
                        <!-- If in stock -->
                        @else
                        <h3 class="incaps no-margin" id="in_stock"><i class="fa fa fa-check-circle-o color-in"></i> In
                            stock </h3>
                        @endif
                        @endif
                        @endif
                    </div>
                </div>

                <!-- Get all the attribute and attribute items of the product and loads automatically price and variant based on the attribute selection
                                                Linked to JS and CSS product-details-attribute-selection -->
                <?php $i = 1; ?>
                @foreach ($attributes as $attr)
                @if ($attr->is_main == 1)
                <!-- if the main attribute is an IMAGE (==1)-->
                <label class="no-margin-bottom">{{ $attr->name }}:</label>
                <div class="row contentBox">
                    <div class="col-lg-12">
                        <div class="">
                            @foreach ($attrItems as $item)
                            @if ($attr->attribute_id == $item->attribute_id)
                            <label class="radio_container" style="font-weight:500;">
                                <input type="radio" id="attribute_item_{{ $item->attribute_item_id }}"
                                    name="attribute_{{ $attr->attribute_id }}"
                                    class="attribute_selection main_attribute" value="{{ $item->attribute_item_id }}"
                                    dimmed="true">
                                <!-- DO NOT REMOVE INPUT -->
                                <span class="checkmark" id="checkmark_{{ $item->attribute_item_id }}" style="background:url('{{ getenv('S3_URL') }}/attribute_items/thumbs/{{ $item->attribute_item_img }}'); 
                                                background-size: 50px 50px;
                                                height:50px;
                                                width:50px;">
                                </span>
                            </label>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <?php $i++; ?>
                @elseif($attr->is_main == 2)
                <!-- if the main attribute is a COLOR (==2)-->
                <label class="no-margin-bottom">{{ $attr->name }}:</label>
                <div class="row contentBox">
                    <div class="col-lg-12">
                        <div class="">
                            @foreach ($attrItems as $item)
                            @if ($attr->attribute_id == $item->attribute_id)
                            <label class="radio_container" style="font-weight:500;">
                                <input type="radio" id="attribute_item_{{ $item->attribute_item_id }}"
                                    name="attribute_{{ $attr->attribute_id }}" class="attribute_selection"
                                    value="{{ $item->attribute_item_id }}" dimmed="true"> <!-- DO NOT REMOVE INPUT -->
                                <span class="checkmark" id="checkmark_{{ $item->attribute_item_id }}"
                                    title="{{ $item->item_name }}" style="background-color:{{ $item->color }} !important; 
                                                background-size: 50px 50px;
                                                height:50px;
                                                width:50px;">
                                </span>
                            </label>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
                @else
                <label class="no-margin-bottom">{{ $attr->name }}:</label>
                <div class="row contentBox">
                    <div class="col-lg-12">
                        <div class="">
                            @foreach ($attrItems as $item)
                            @if ($attr->attribute_id == $item->attribute_id)
                            <label class="radio_container" style="font-weight:500;">
                                <input type="radio" id="attribute_item_{{ $item->attribute_item_id }}"
                                    name="attribute_{{ $attr->attribute_id }}" class="attribute_selection"
                                    value="{{ $item->attribute_item_id }}" dimmed="true">
                                <!-- DO NOT REMOVE INPUT -->
                                <span class="checkmark" id="checkmark_{{ $item->attribute_item_id }}">
                                    {{ $item->item_name }} </span>
                            </label>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
                @endif
                @endforeach


                <!-- Selection of quantity of products to order
                                                PRODUCT QTY TO ORDER. Enabled only when all attributes are selected -->
                <div class="contentBox">
                    <span class="selected-color"><label>Quantity</label></span>
                    <!-- If there is at least 1 attribute associated to the product, we set the quantity input as disabled. We can only enable the quantity when all the attributes are selected -->
                    @if (count($attributes) != 0)
                    <input class="form-control qty_input_2" id="order_qty" type="number" value="1" max="100" min="0"
                        disabled />
                    <!-- If no attribute is linked to the product, the quantity input will be enabled by default -->
                    @else
                    <!-- If Stock management is active (counting stock) -->
                    @if ($product[0]->enable_stock_mgmt == 1)
                    <!-- If no stock quantity -->
                    @if ($variants[0]->stock_qty == 0 or $variants[0]->stock_qty == null)
                    <input class="form-control qty_input_2" id="order_qty" type="number" value="1" max="100" min="0"
                        disabled />
                    <!-- If stock available -->
                    @else
                    <input class="form-control qty_input_2" id="order_qty" type="number" value="1" max="100" min="0" />
                    @endif
                    <!-- If stock management inactive (not counting stock) -->
                    @else
                    <!-- If out of stock -->
                    @if ($variants[0]->stock_status_id == 0 or $variants[0]->stock_status_id == null)
                    <input class="form-control qty_input_2" id="order_qty" type="number" value="1" max="100" min="0"
                        disabled />
                    <!-- If in stock -->
                    @else
                    <input class="form-control qty_input_2" id="order_qty" type="number" value="1" max="100" min="0" />
                    @endif
                    @endif
                    @endif
                </div>

                <!-- Add to cart actions with control on stock availability -->
                <div class="cart-actions">
                    <div class="addto row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            @if (count($attributes) != 0)
                            <button class="button btn-block btn-cart cart first add_to_cart" title="Add to Cart"
                                type="button" href="" qty="1" variantid="" disabled>
                                Add to Cart
                            </button>
                            @else
                            <button class="button btn-block btn-cart cart first add_to_cart" title="Add to Cart"
                                type="button" href="" qty="1" variantid="{{ $variants[0]->variant_id }}">
                                Add to Cart
                            </button>
                            @endif
                        </div>
                    </div>
                    <div style="clear:both"></div>

                    <div class="hidden-xs">
                        <!-- If there is at least 1 attribute associated to the product, the stock status of the variant
                                                    will be loaded dynamically from the Javascript in the in_stock class -->
                        @if (count($attributes) != 0)
                        <h3 class="incaps" id="in_stock"></h3>
                        <!-- If no attribute is linked to the product, the stock status will be the one of the unique variant associated to the product -->
                        @else
                        <!-- If Stock management is active (counting stock) -->
                        @if ($product[0]->enable_stock_mgmt == 1)
                        <!-- If no stock quantity -->
                        @if ($variants[0]->stock_qty == 0 or $variants[0]->stock_qty == null)
                        <h3 class="incaps" id="in_stock"><i class="fa fa fa-times-circle color-out"></i>
                            Out of stock</h3>
                        <!-- If stock available -->
                        @else
                        <h3 class="incaps" id="in_stock"><i class="fa fa fa-check-circle-o color-in"></i> In stock
                            ({{ $variants[0]->stock_qty }} items remaining)</h3>
                        @endif
                        <!-- If stock management inactive (not counting stock) -->
                        @else
                        <!-- If out of stock -->
                        @if ($variants[0]->stock_status_id == 0 or $variants[0]->stock_status_id == null)
                        <h3 class="incaps" id="in_stock"><i class="fa fa fa-times-circle color-out"></i>
                            Out of stock</h3>
                        <!-- If in stock -->
                        @else
                        <h3 class="incaps" id="in_stock"><i class="fa fa fa-check-circle-o color-in"></i> In stock </h3>
                        @endif
                        @endif
                        @endif
                    </div>
                    <h3 class="incaps" id="secure"><i class="glyphicon glyphicon-lock"></i> Secure online ordering
                    </h3>
                </div>
                <div class="clear"></div>

                <!-- Add to cart actions with control on stock availability -->
                <div class="product-tab w100 clearfix">
                    <ul class="nav nav-tabs">
                        <li class="active"><a onclick="onTabClick('details')" href="#details"
                                data-toggle="tab">Details</a></li>
                        <li class=""><a onclick="onTabClick('video')" href="#video" data-toggle="tab">Video</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" id="details">
                        <!-- DESCRIPTION -->
                        <div class="tab-pane active" id="details">
                            <?php echo htmlspecialchars_decode($product[0]->description); ?>
                        </div>

                    </div>
                    <div class="tab-content" id="video">
                        <!-- DESCRIPTION -->
                        <div class="tab-pane" id="video">
                            <iframe width="420" height="315" src="{{$video[0]->link}}">
                            </iframe>
                        </div>
                    </div>


                </div>
                <!--/.product-tab-->

                <div style="clear:both"></div>
                <div class="product-share clearfix">
                    <p> SHARE </p>
                    <div class="socialIcon">
                        <a class="facebook customer share" title="Facebook share"
                            href="http://www.facebook.com/sharer.php?u={{ url()->full() }}" target="_blank"> <i
                                class="fa fa-facebook"></i> </a>
                        <a class="twitter customer share" title="Twitter share"
                            href="https://twitter.com/share?url={{ url()->full() }}" target="_blank"> <i
                                class="fa fa-twitter"></i> </a>
                        <!-- Print -->
                        <a href="javascript:;" onclick="window.print()"> <i class="fa fa-print"></i> </a>
                    </div>
                </div>
                <!--/.product-share-->
            </div>
            <!--/ RIGHT COLUMN END -->
        </div>
        <!--/.row-->
    </div>
</div>

<div class="container">

    <!-- Recommended Products -->
    @if ($similarProducts)
    <div class="row recommended">
        <h1> YOU MAY ALSO LIKE </h1>

        <div id="SimilarProductSlider">

            @foreach ($similarProducts as $p)
            <div class="item">
                <?php ProductsController::displayProductElement($p); ?>
            </div>
            <!--/.item-->
            @endforeach

        </div>

    </div>
    <!--/.recommended-->

    <div style="clear:both"></div>
    @endif


    <!-- Linked Products -->
    @if ($linkedProducts)
    <div class="row recommended">
        <h1> LINKED PRODUCTS </h1>

        <div id="LinkedProductSlider">

            @foreach ($linkedProducts as $product)
            <div class="item">
                <?php ProductsController::displayProductElement($product); ?>
            </div>
            <!--/.item-->
            @endforeach

        </div>

        <div class="owl-controls clickable">
            <div class="owl-pagination">
                <div class="owl-has-nav owl-prev"><i class="fa fa-angle-left"></i> </div>
                <div class="owl-page active"><span class=""></span></div>
                <div class="owl-page"><span class=""></span></div>
                <div class="owl-has-nav owl-next"><i class="fa fa-angle-right"></i> </div>
            </div>
        </div>
        <!--/.SimilarProductSlider-->

    </div>
    <!--/.recommended-->

    <div style="clear:both"></div>
    @endif

</div>

<div class="gap"></div>

<!-- Modal after add to cart start -->
@include('frontend.products.includes.modal-add-to-cart')


<script src="/js/jquery/jquery-2.1.3.min.js"></script>
<!-- JS to manage dynamically the selection of attributes, variants and prices of the product -->

<script type="text/javascript">
    <?php if (is_array($product) && count($product) > 0): ?>
        var product_id = <?= json_encode($product[0]->product_id) ?>;
    <?php elseif (is_object($product)): ?>
        var product_id = <?= json_encode($product->product_id) ?>;
    <?php else: ?>
       
    <?php endif; ?>

    var attributes = <?= json_encode($attributes) ?>;

    $("#order_qty").keyup(function() {
        $('#add_to_cart').attr('qty', this.value);
    });


</script>

<script src="/js/products/product-details-attribute-selection.js"></script>
<script src="/js/products/product-details-add-to-cart.js"></script>

<script>
    const onTabClick =(value) =>{
        const tabsPane = document.querySelectorAll(".tab-pane");
        console.log(tabsPane);
        console.log(value);
        tabsPane.forEach(element => {
            console.log(element.id==value)

            if(element.id==value){
               element.classList.add("active")
            }else{
              element.classList.remove("active")
            }
            
        });
    }
</script>

@endsection