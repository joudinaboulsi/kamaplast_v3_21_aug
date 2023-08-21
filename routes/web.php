<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// home page
Route::get('/', [
    'as' => 'home_path',
    'uses' => 'Frontend\PagesController@home',
]);

// home page
Route::get('/deals', [
    'as' => 'deals_path',
    'uses' => 'Frontend\PagesController@deals',
]);

// collections page
Route::get('/collections', [
    'as' => 'collections_path',
    'uses' => 'Frontend\PagesController@collections',
]);

// categories page
Route::get('/all-categories', [
    'as' => 'categories_path',
    'uses' => 'Frontend\PagesController@categories',
]);

// categories page
Route::get('/best-sellers', [
    'as' => 'best_sellers_path',
    'uses' => 'Frontend\PagesController@bestSellers',
]);

// product fo collection page
Route::get('/collections/{tag_name}-{tag_id}', [
    'as' => 'product_of_collection_path',
    'uses' => 'Frontend\PagesController@getProductsOfCollection',
]);

// get search - search bar ajax call with JSON response
Route::post('/get-search', [
    'as' => 'get_search_path',
    'uses' => 'Frontend\PagesController@getSearch',
]);

// search result page -get
Route::get('/search-result', [
    'as' => 'search_result_path',
    'uses' => 'Frontend\PagesController@getSearchResults',
]);

// search result page -post
Route::post('/search-result', [
    'as' => 'search_result_path',
    'uses' => 'Frontend\PagesController@searchResult',
]);

// about page
Route::get('/about-us', [
    'as' => 'about_path',
    'uses' => 'Frontend\PagesController@about',
]);
// our story page
Route::get('/our-story', [
    'as' => 'story_path',
    'uses' => 'Frontend\PagesController@ourStory',
]);

// about page
Route::get('/odm', [
    'as' => 'odm_path',
    'uses' => 'Frontend\PagesController@odm',
]);

// media page
Route::get('/media', [
    'as' => 'media_path',
    'uses' => 'Frontend\MediaController@media',
]);

// tutorials page
Route::get('/tutorials', [
    'as' => 'tutorials_path',
    'uses' => 'Frontend\MediaController@tutorials',
]);

// brands page
Route::get('/all-brands', [
    'as' => 'brands_path',
    'uses' => 'Frontend\BrandsController@brands',
]);

// brand details page
Route::get('/brand-details', [
    'as' => 'brand_details_path',
    'uses' => 'Frontend\BrandsController@brandDetails',
]);

// services page
Route::get('/services', [
    'as' => 'services_path',
    'uses' => 'Frontend\ServicesController@services',
]);

// service details page
Route::get('/service-details', [
    'as' => 'service_details_path',
    'uses' => 'Frontend\ServicesController@serviceDetails',
]);

// contact page
Route::get('/contact-us', [
    'as' => 'contact_path',
    'uses' => 'Frontend\PagesController@contact',
]);

// contact page
Route::post('/contact-us', [
    'as' => 'contact_path',
    'uses' => 'Frontend\PagesController@getContactForm',
]);

// terms and conditions page
Route::get('/terms-and-conditions', [
    'as' => 'terms_path',
    'uses' => 'Frontend\PagesController@terms',
]);

// privacy policy page
Route::get('/privacy-policy', [
    'as' => 'privacy_path',
    'uses' => 'Frontend\PagesController@privacy',
]);

// disclaimer page
Route::get('/disclaimer', [
    'as' => 'disclaimer_path',
    'uses' => 'Frontend\PagesController@disclaimer',
]);

// careers page
Route::get('/careers', [
    'as' => 'careers_path',
    'uses' => 'Frontend\PagesController@careers',
]);

// careers page
Route::post('/careers', [
    'as' => 'careers_path',
    'uses' => 'Frontend\PagesController@getCareersForm',
]);

// faq page
Route::get('/faq', [
    'as' => 'faq_path',
    'uses' => 'Frontend\PagesController@faq',
]);

// blog page
Route::get('/blog', [
    'as' => 'blog_path',
    'uses' => 'Frontend\BlogController@blog',
]);

// blog details page
Route::get('/blog-details-{blog_id}', [
    'as' => 'blog_details_path',
    'uses' => 'Frontend\BlogController@blogDetails',
]);

// Product List Page
Route::get('/product-list/{seo_category_link}', [
    'as' => 'product_list_path',
    'uses' => 'Frontend\ProductsController@category',
]);

//Product details page
Route::get('/product/{seo_url}', [
    'as' => 'product_details_path',
    'uses' => 'Frontend\ProductsController@product',
]);

// Ajax call to get all variants by selecting attributes
Route::post('/get-variants-from-attributes', [
    'as' => 'variant_from_attributes_path',
    'uses' => 'Frontend\ProductsController@getVariantFromAttributes',
]);

// set the sessions of the selected filter
Route::post('/set-filter-session', [
    'as' => 'set_filter_session_path',
    'uses' => 'Frontend\ProductsController@setFilterSession',
]);

// sort by ascending descending price
Route::post('/sort-by', [
    'as' => 'sort_by_path',
    'uses' => 'Frontend\ProductsController@sortProducts',
]);

// set the sessions of the selected filter
Route::post('/filter-price-range', [
    'as' => 'filter_price_range_path',
    'uses' => 'Frontend\ProductsController@filterByPriceRange',
]);

// loading the dynamic products after filtering
Route::get('/load-dynamic-products-{category_id}', [
    'as' => 'load_dynamic_products_path',
    'uses' => 'Frontend\ProductsController@loadDynamicProducts',
]);

// loading the dynamic products after sorting
Route::get('/display-sorted', [
    'as' => 'display_sorted_path',
    'uses' => 'Frontend\ProductsController@displaySorted',
]);

// -------------------------------------//
// ============= CART ==================//
// -------------------------------------//

//show cart
Route::get('/cart', [
    'as' => 'cart_path',
    'uses' => 'Frontend\CartController@show',
]);

//add to cart
Route::post('/add-edit-cart', [
    'as' => 'add_edit_cart_path',
    'uses' => 'Frontend\CartController@addEditCart',
]);

//get the cart total prices
Route::post('/get-cart-totals', [
    'as' => 'get_cart_totals_path',
    'uses' => 'Frontend\CartController@getCartTotalsAjax',
]);

//delete an item from the cart - ONLINE
Route::post('/delete-cart-item', [
    'as' => 'delete-cart_item_path',
    'uses' => 'Frontend\CartController@deleteCartItem',
]);

//delete an item from the session cart - OFFLINE
Route::post('/delete-cart-item-offline', [
    'as' => 'delete-cart_item_offline_path',
    'uses' => 'Frontend\CartController@deleteCartItemOffline',
]);

// -----------------------------------------//
// ============= CHECKOUT ==================//
// -----------------------------------------//

//show checkout
Route::get('/checkout', [
    'as' => 'checkout_path',
    'uses' => 'Frontend\CheckoutController@showCheckout',
]);

//get address info from address id
Route::post('/get-user-address-details', [
    'as' => 'get_user_address_details_path',
    'uses' => 'Frontend\UsersController@getAddressFromAddressId',
]);

//apply promo code to checkout
Route::post('/apply-promo', [
    'as' => 'apply_promo_path',
    'uses' => 'Frontend\PromoController@applyPromo',
]);

//clear promo code
Route::get('/clear-promo', [
    'as' => 'clear_promo_path',
    'uses' => 'Frontend\CheckoutController@clearPromoCode',
]);

//save shipping address / delivery details and proceed to payment methods
Route::post('/save-shipping-delivery', [
    'as' => 'save_shipping_address_delivery_path',
    'uses' => 'Frontend\CheckoutController@saveShippingDelivery',
]);

//show payment page
Route::get('/payment', [
    'as' => 'payment_path',
    'uses' => 'Frontend\CheckoutController@showPaymentMethods',
]);

// pay process
Route::post('/payment-response', [
    'as' => 'pay_path',
    'uses' => 'Frontend\CheckoutController@pay',
]);

Auth::routes();

//User Account page
Route::get('/account', [
    'as' => 'user_account_path',
    'uses' => 'Frontend\UsersController@show',
]);

// add user address
Route::post('/add-address', [
    'as' => 'add_user_address_path',
    'uses' => 'Frontend\UsersController@addAddress',
]);

// add user address
Route::post('/get-address-details-for-user', [
    'as' => 'to_edit_user_address_path',
    'uses' => 'Frontend\UsersController@getAddressFromAddressId',
]);

// edit user address
Route::post('/edit-address', [
    'as' => 'edit_user_address_path',
    'uses' => 'Frontend\UsersController@updateAddress',
]);

// delete user address
Route::post('/delete-user-address', [
    'as' => 'delete_address_path',
    'uses' => 'Frontend\UsersController@deleteAddress',
]);

// edit user information
Route::post('/edit-info', [
    'as' => 'edit_user_information_path',
    'uses' => 'Frontend\UsersController@updateInfo',
]);

// edit user password
Route::post('/edit-password', [
    'as' => 'edit_user_password_path',
    'uses' => 'Frontend\UsersController@updatePassword',
]);

// delete from wishlist
Route::post('/delete-from-wishlist', [
    'as' => 'delete_from_wishlist_path',
    'uses' => 'Frontend\UsersController@deleteFromWishlist',
]);

// User Order status page
Route::get('/user-orders-status-{order_id}', [
    'as' => 'user_order_status_path',
    'uses' => 'Frontend\UsersController@showOrderStatus',
]);

// user logout
Route::get('/user-logout', [
    'as' => 'user_logout_path',
    'uses' => 'Auth\LoginController@userLogout',
]);

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// =============== ADMIN LOGIN =============================

// admin logout
Route::get('/admin-logout', [
    'as' => 'admin_logout_path',
    'uses' => 'Auth\AdminLoginController@logout',
]);

// admin login POST
Route::post('/admin', [
    'as' => 'admin_login_path',
    'uses' => 'Auth\AdminLoginController@login',
]);

// admin login view
Route::get('/admin', [
    'as' => 'admin_path',
    'uses' => 'Auth\AdminLoginController@showLoginForm',
]);

// =============== CATEGORY MANAGEMENT =======================

//Page for category management
Route::get('/categories', [
    'as' => 'cms_category',
    'uses' => 'CategoryController@index',
]);

// add category
Route::post('/add-category', [
    'as' => 'add_category_path',
    'uses' => 'CategoryController@add',
]);

//edit category
Route::post('/categories', [
    'as' => 'cms_edit_category',
    'uses' => 'CategoryController@update',
]);

//get category data from id
Route::post('/get-category-data-from-id', [
    'as' => 'get_category_data_path',
    'uses' => 'CategoryController@getCategoryDataFromId',
]);

//Highlight category
Route::post('/highlight-category', [
    'as' => 'highlight_category_path',
    'uses' => 'CategoryController@highlightCategory',
]);

//delete category
Route::post('/delete-category', [
    'as' => 'delete_category_path',
    'uses' => 'CategoryController@delete',
]);

// =============== TAGS MANAGEMENT =======================

//Page for tags management
Route::get('/tags', [
    'as' => 'cms_tags',
    'uses' => 'TagsController@index',
]);

// add tag
Route::post('/add-tag', [
    'as' => 'add_tag_path',
    'uses' => 'TagsController@add',
]);

//edit tag
Route::post('/edit-tag', [
    'as' => 'cms_edit_tag',
    'uses' => 'TagsController@update',
]);

//edit tag
Route::post('/update-collection-tag', [
    'as' => 'update_collection_tag',
    'uses' => 'TagsController@updateCollectionTag',
]);

//get tag data from id
Route::post('/get-tag-data-from-id', [
    'as' => 'get_tag_data_path',
    'uses' => 'TagsController@getTagDataFromId',
]);

//delete tag
Route::post('/delete-tag', [
    'as' => 'delete_tag_path',
    'uses' => 'TagsController@delete',
]);

// =============== BRANDS MANAGEMENT =======================

//Page for brands management
Route::get('/brands', [
    'as' => 'cms_brands',
    'uses' => 'BrandsController@index',
]);

// add brand
Route::post('/add-brand', [
    'as' => 'add_brand_path',
    'uses' => 'BrandsController@add',
]);

//edit brand
Route::post('/edit_brand', [
    'as' => 'cms_edit_brand',
    'uses' => 'BrandsController@update',
]);

//get brand data from id
Route::post('/get-brand-data-from-id', [
    'as' => 'get_brand_data_path',
    'uses' => 'BrandsController@getBrandDataFromId',
]);

//delete brand
Route::post('/delete-brand', [
    'as' => 'delete_brand_path',
    'uses' => 'BrandsController@delete',
]);

// =============== PRODUCTS MANAGEMENT =======================

//Page for products management
Route::get('/products', [
    'as' => 'cms_products',
    'uses' => 'ProductController@index',
]);

//DO NOT CHANGE THE URL -- Connected to AJAX in CMS List of Products!!!!!!!!
Route::post('/load-product-table', [
    'as' => 'cms_load_product_table',
    'uses' => 'ProductController@loadProductTable',
]);

Route::post('/load-product-table-to-link', [
    'as' => 'cms_load_product_table',
    'uses' => 'ProductController@loadProductTableToLink',
]);

Route::post('/products-load', [
    'as' => 'cms_products_ajax',
    'uses' => 'ProductController@load',
]);

//Page for product details
Route::get('/product-{product_id}', [
    'as' => 'cms_product_details_path',
    'uses' => 'ProductController@showDetails',
]);

//Form to add a product
Route::get('/add-product', [
    'as' => 'cms_create_products',
    'uses' => 'ProductController@create',
]);

// add product
Route::post('/add-product', [
    'as' => 'cms_add_product_path',
    'uses' => 'ProductController@add',
]);

//delete product
Route::post('/delete-product', [
    'as' => 'cms_delete_product_path',
    'uses' => 'ProductController@delete',
]);

//ADD product Tag
Route::post('/add-product-tag', [
    'as' => 'add_product_tag_path',
    'uses' => 'ProductController@addTag',
]);

//Delete product Tag
Route::post('/delete-prod-tag', [
    'as' => 'delete_product_tag_path',
    'uses' => 'ProductController@deleteTag',
]);

//ADD product Video
Route::post('/add-product-video', [
    'as' => 'add_product_video_path',
    'uses' => 'ProductController@addVideo',
]);

//Delete product Video
Route::post('/delete-prod-video', [
    'as' => 'delete_product_video_path',
    'uses' => 'ProductController@deleteVideo',
]);

//ADD Linked Product
Route::post('/add-linked-product', [
    'as' => 'add_product_link_path',
    'uses' => 'ProductController@addLinkedProduct',
]);

//Delete Link between Products
Route::post('/delete-prod-link', [
    'as' => 'delete_product_link_path',
    'uses' => 'ProductController@deleteLinkedProduct',
]);

//update product basic info
Route::post('/update-product-basic-info', [
    'as' => 'edit_product_basic_info_path',
    'uses' => 'ProductController@updateBasicInfo',
]);

//update product basic info
Route::post('/update-position-of-product', [
    'as' => 'update_position_of_product_path',
    'uses' => 'ProductController@updateProdcutPosition',
]);

//ADD product image
Route::post('/add-image', [
    'as' => 'add_product_img_path',
    'uses' => 'ProductController@addImage',
]);

//set image as primary
Route::post('/img-set-primary', [
    'as' => 'set_primary_path',
    'uses' => 'ProductController@setPrimary',
]);

//delete product image
Route::post('/delete-image', [
    'as' => 'cms_delete_product_img_path',
    'uses' => 'ProductController@deleteImage',
]);

//add main attribute items
Route::post('/add-main-attribute-item', [
    'as' => 'add_main_attribute_items_path',
    'uses' => 'ProductController@addMainAttributeItem',
]);

//update main attribute name
Route::post('/edit-main-attr-name', [
    'as' => 'edit_main_attr_name_path',
    'uses' => 'ProductController@updateMainAttributeName',
]);

//get attribute item info from id
Route::post('/get-main-attr-item-from-id', [
    'as' => 'get_main_attr_item_path',
    'uses' => 'ProductController@getMainAttributeItemFromId',
]);

//update main attribute item
Route::post('/edit-main-attr-item', [
    'as' => 'edit_main_attribute_item_path',
    'uses' => 'ProductController@updateMainAttributeItem',
]);

//delete product attribute
Route::post('/delete-main-attr-item', [
    'as' => 'delete_main_attr_item_path',
    'uses' => 'ProductController@deleteMainAttributeItem',
]);

//add product attribute
Route::post('/add-attribute', [
    'as' => 'add_attribute_path',
    'uses' => 'ProductController@addAttribute',
]);

//get attribute info from id
Route::post('/get-attr-from-id', [
    'as' => 'get_attribute_path',
    'uses' => 'ProductController@getAttributeFromId',
]);

//update attribute
Route::post('/edit-attribute', [
    'as' => 'edit_attribute_path',
    'uses' => 'ProductController@updateAttribute',
]);

//delete product attribute
Route::post('/delete-attribute', [
    'as' => 'delete_attribute_path',
    'uses' => 'ProductController@deleteAttribute',
]);

//add product variant
Route::post('/add-variant', [
    'as' => 'add_variant_path',
    'uses' => 'ProductController@addVariant',
]);

//get variant info from id
Route::post('/get-variant-from-id', [
    'as' => 'get_variant_path',
    'uses' => 'ProductController@getVariantFromId',
]);

//update variant
Route::post('/update-variant', [
    'as' => 'update_variant_path',
    'uses' => 'ProductController@updateVariant',
]);

//publish/unpublish variant
Route::post('/publish-variant-from-id', [
    'as' => 'publish_variant_from_id_path',
    'uses' => 'ProductController@publishVariant',
]);

//delete product variant
Route::post('/delete-variant', [
    'as' => 'delete_variant_path',
    'uses' => 'ProductController@deleteVariant',
]);

//delete product variant
Route::post('/delete-all-product-variants', [
    'as' => 'delete_all_product_variants_path',
    'uses' => 'ProductController@deleteAllVariants',
]);

//edit product variant promo
Route::post('/edit-variant-promo', [
    'as' => 'edit_variant_promo_path',
    'uses' => 'ProductController@editVariantPromo',
]);

//get variant promo from variant id
Route::post('/get-promo-from-variant-id', [
    'as' => 'get_variant_promo_path',
    'uses' => 'ProductController@getPromoFromVariantId',
]);

//edit the primary image of the product
Route::post('/edit-primary-image', [
    'as' => 'edit_primary_image_path',
    'uses' => 'ProductController@editPrimaryImage',
]);

//link product to a category
Route::post('/link-category', [
    'as' => 'link_category_to_product_path',
    'uses' => 'ProductController@linkCategory',
]);

//unlink product from a category
Route::post('/unlink-category', [
    'as' => 'unlink_category_to_product_path',
    'uses' => 'ProductController@unlinkCategory',
]);

//Publish|Unpublish product
Route::post('/publish-product-from-id', [
    'as' => 'publish_product_from_id_path',
    'uses' => 'ProductController@publish',
]);

//Feature|UnFeature product
Route::post('/feature-product-from-id', [
    'as' => 'feature_product_from_id_path',
    'uses' => 'ProductController@feature',
]);

//update product description info
Route::post('/update-product-description-info', [
    'as' => 'edit_product_description_info_path',
    'uses' => 'ProductController@updateDescriptionInfo',
]);

//update product SEO info
Route::post('/update-product-seo-info', [
    'as' => 'edit_product_seo_info_path',
    'uses' => 'ProductController@updateSeoInfo',
]);

// =============== PROMO CODES MANAGEMENT =======================

//Page for Promo Codes management
Route::get('/promo-codes', [
    'as' => 'cms_promo_codes',
    'uses' => 'PromoCodesController@index',
]);

//Page for add Promo Code
Route::get('/add-promo-code', [
    'as' => 'cms_add_promo_code_path',
    'uses' => 'PromoCodesController@addPromoPage',
]);

//Add Promo Code
Route::post('/add-promo', [
    'as' => 'add_promo',
    'uses' => 'PromoCodesController@add',
]);

//Update Promo Code
Route::post('/edit-promo', [
    'as' => 'edit_promo_code_path',
    'uses' => 'PromoCodesController@update',
]);

//Publish|Unpublish Promo Code
Route::post('/publish-promo-code-from-id', [
    'as' => 'publish_promo_code_from_id_path',
    'uses' => 'PromoCodesController@publish',
]);

//Delete Promo Code
Route::post('/delete-promo-code', [
    'as' => 'delete_promo_code_path',
    'uses' => 'PromoCodesController@delete',
]);

//Page for Promo Code details
Route::get('/promo-code-{promo_code_id}', [
    'as' => 'cms_promo_code_details_path',
    'uses' => 'PromoCodesController@showDetails',
]);

// =============== USERS MANAGEMENT =======================

//Page for users management
Route::get('/users', [
    'as' => 'cms_users',
    'uses' => 'UsersController@index',
]);

//Page to load users datatable
Route::post('/load-users-table', [
    'as' => 'cms_load_users_table',
    'uses' => 'UsersController@loadUsersTable',
]);

//Page for adding a user
Route::get('/add-users', [
    'as' => 'cms_add_users',
    'uses' => 'UsersController@addUserPage',
]);

//Add user
Route::post('/add-user', [
    'as' => 'add_user_path',
    'uses' => 'UsersController@add',
]);

//Page for user details
Route::get('/user-details-{user_id}', [
    'as' => 'cms_users_details',
    'uses' => 'UsersController@showDetails',
]);

//Update user
Route::post('/edit-user', [
    'as' => 'edit_user_info_path',
    'uses' => 'UsersController@updateUserInfo',
]);

//ADD user Tag
Route::post('/add-user-tag', [
    'as' => 'add_user_tag_path',
    'uses' => 'UsersController@addTag',
]);

//Delete user Tag
Route::post('/delete-user-tag', [
    'as' => 'delete_user_tag_path',
    'uses' => 'UsersController@deleteTag',
]);

//get address info from address id
Route::post('/get-address-details', [
    'as' => 'get_address_details_path',
    'uses' => 'UsersController@getAddressFromAddressId',
]);

//ADD address to user
Route::post('/add-user-address', [
    'as' => 'add_address_path',
    'uses' => 'UsersController@addAddress',
]);

//Update a user address
Route::post('/edit-user-address', [
    'as' => 'edit_address_path',
    'uses' => 'UsersController@updateAddress',
]);

//Delete user address
Route::post('/delete-address', [
    'as' => 'delete_user_address_path',
    'uses' => 'UsersController@deleteAddress',
]);

//Publish|Unpublish user
Route::post('/publish-user-from-id', [
    'as' => 'publish_user_from_id_path',
    'uses' => 'UsersController@publish',
]);

//Delete user
Route::post('/delete-user', [
    'as' => 'delete_user_path',
    'uses' => 'UsersController@delete',
]);

// =============== ORDERS MANAGEMENT =======================

//Page for orders management
Route::get('/orders', [
    'as' => 'cms_orders',
    'uses' => 'OrdersController@index',
]);

//Page to load orders datatable
Route::post('/load-orders-table', [
    'as' => 'cms_load_orders_table',
    'uses' => 'OrdersController@loadOrdersTable',
]);

//Page for orders management
Route::get('/order-details-{order_id}', [
    'as' => 'cms_order_details',
    'uses' => 'OrdersController@showDetails',
]);

//Page for abandoned checkouts
Route::get('/abandoned-checkouts', [
    'as' => 'cms_abandoned_checkouts',
    'uses' => 'OrdersController@showDetails',
]);

//Update shipping address
Route::post('/edit-shipping-address', [
    'as' => 'edit_shipping_address_path',
    'uses' => 'OrdersController@updateShippingAddress',
]);

//Update order payment and delivery statuses
Route::post('/edit-order', [
    'as' => 'edit_order_path',
    'uses' => 'OrdersController@updateOrder',
]);

// =============== HOME MANAGEMENT =======================

//Page for home
Route::get('/cms-home', [
    'as' => 'cms_home',
    'uses' => 'HomeController@index',
]);

// add Slide/Separator
Route::post('/add-slide', [
    'as' => 'add_slide_path',
    'uses' => 'HomeController@addSlide',
]);

//get Slide/Separator data from id
Route::post('/get-slide-data-from-id', [
    'as' => 'get_slide_data_path',
    'uses' => 'HomeController@getSlideDataFromId',
]);

//edit Slide/Separator
Route::post('/edit-slide', [
    'as' => 'cms_edit_slide',
    'uses' => 'HomeController@updateSlide',
]);

//delete Slide/Separator
Route::post('/delete-slide', [
    'as' => 'delete_slide_path',
    'uses' => 'HomeController@deleteSlide',
]);

// =============== ABOUT MANAGEMENT =======================

//Page for about
Route::get('/cms-about', [
    'as' => 'cms_about',
    'uses' => 'AboutController@index',
]);

//edit About
Route::post('/edit-about', [
    'as' => 'cms_edit_about',
    'uses' => 'AboutController@update',
]);

// =============== BLOG MANAGEMENT =======================

//Page for Media
Route::get('/cms-media', [
    'as' => 'cms_blog',
    'uses' => 'BlogController@index',
]);

// add News
Route::post('/add-news', [
    'as' => 'add_news_path',
    'uses' => 'BlogController@addNews',
]);

//Show News Details Page
Route::get('/news-details-{news_id}', [
    'as' => 'cms_news_details_path',
    'uses' => 'BlogController@showDetails',
]);

//edit News
Route::post('/edit-news', [
    'as' => 'cms_edit_news',
    'uses' => 'BlogController@updateNews',
]);

//ADD News Tag
Route::post('/add-news-tag', [
    'as' => 'add_news_tag_path',
    'uses' => 'BlogController@addTag',
]);

//Delete News Tag
Route::post('/delete-news-tag', [
    'as' => 'delete_news_tag_path',
    'uses' => 'BlogController@deleteTag',
]);

//delete News
Route::post('/delete-news', [
    'as' => 'delete_news_path',
    'uses' => 'BlogController@deleteNews',
]);

// =============== VIDEOS MANAGEMENT =======================

//Page for TUTORIALS
Route::get('/cms-tutorials', [
    'as' => 'cms_videos',
    'uses' => 'VideosController@index',
]);

// add Video
Route::post('/add-tutorial', [
    'as' => 'add_video_path',
    'uses' => 'VideosController@addVideo',
]);

//delete Video
Route::post('/delete-tutorial', [
    'as' => 'delete_video_path',
    'uses' => 'VideosController@deleteVideo',
]);

// =============== CAREERS MANAGEMENT =======================
//Page for careers
Route::get('/cms-careers', [
    'as' => 'cms_careers',
    'uses' => 'CareersController@index',
]);
//add careers
Route::post('/add-careers', [
    'as' => 'cms_add_careers',
    'uses' => 'CareersController@add',
]);
//update careers
Route::post('/update-careers', [
    'as' => 'cms_edit_careers',
    'uses' => 'CareersController@edit',
]);
//get careers by id
Route::post('/get-careers-by-id', [
    'as' => 'cms_get_careers_by_id',
    'uses' => 'CareersController@getCareersById',
]);
//delete careers
Route::post('/delete-careers', [
    'as' => 'delete_careers_path',
    'uses' => 'CareersController@delete',
]);

// =============== SEO MANAGEMENT =======================

//Page for SEO
Route::get('/seo', [
    'as' => 'cms_seo',
    'uses' => 'SeoController@index',
]);

//get page seo to edit
Route::post('/get-page-seo-from-id', [
    'as' => 'cms_seo_details_path',
    'uses' => 'SeoController@getSEOToEdit',
]);

//edit seo
Route::post('/edit-page-seo', [
    'as' => 'cms_edit_page_seo',
    'uses' => 'SeoController@updateSEO',
]);

//edit OG Twitter
Route::post('/edit-og-twitter', [
    'as' => 'cms_edit_og_twitter',
    'uses' => 'SeoController@updateOGTwitter',
]);

// =============== STOCK INTEGRATION MANAGEMENT =======================

//Page for stock integration management
Route::get('/stock-integration', [
    'as' => 'cms_stock_integration',
    'uses' => 'StockController@index',
]);

// update stock using excel stock integration
Route::post('/stock-integration', [
    'as' => 'cms_stock_integration_update',
    'uses' => 'StockController@updateStock',
]);

/**
 * Generate dynamic sitemap
 */
Route::get('/sitemap', function () {
    // create new sitemap object
    $sitemap = App::make('sitemap');

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    // by default cache is disabled
    $sitemap->setCache('laravel.sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached()) {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add(
            URL::to('/'),
            '2022-08-02T20:10:00+02:00',
            '1.0',
            'daily'
        );
        $sitemap->add(
            URL::to('about-us'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('coverage'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('videos'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('beauty-campaigns'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('coaching'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('education'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('salon'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('contact-us'),
            '2022-08-02T12:30:00+02:00',
            '0.7',
            'monthly'
        );
        $sitemap->add(
            URL::to('collections'),
            '2022-08-02T12:30:00+02:00',
            '0.6',
            'monthly'
        );
        $sitemap->add(
            URL::to('deals'),
            '2022-08-02T12:30:00+02:00',
            '0.6',
            'monthly'
        );
        $sitemap->add(
            URL::to('terms-and-conditions'),
            '2022-08-02T12:30:00+02:00',
            '0.4',
            'monthly'
        );
        $sitemap->add(
            URL::to('privacy-policy'),
            '2022-08-02T12:30:00+02:00',
            '0.4',
            'monthly'
        );
        $sitemap->add(
            URL::to('disclaimer'),
            '2022-08-02T12:30:00+02:00',
            '0.4',
            'monthly'
        );
        $sitemap->add(
            URL::to('careers'),
            '2022-08-02T12:30:00+02:00',
            '0.4',
            'monthly'
        );
        $sitemap->add(
            URL::to('faq'),
            '2022-08-02T12:30:00+02:00',
            '0.4',
            'monthly'
        );
        $sitemap->add(
            URL::to('cart'),
            '2022-08-02T12:30:00+02:00',
            '0.3',
            'monthly'
        );
        $sitemap->add(
            URL::to('checkout'),
            '2022-08-02T12:30:00+02:00',
            '0.2',
            'monthly'
        );
        $sitemap->add(
            URL::to('payment'),
            '2022-08-02T12:30:00+02:00',
            '0.1',
            'monthly'
        );
        $sitemap->add(
            URL::to('account'),
            '2022-08-02T12:30:00+02:00',
            '0.2',
            'monthly'
        );

        // get all products from db
        $products = DB::table('products')
            ->where('hidden', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // add every product to the sitemap
        foreach ($products as $product) {
            $sitemap->add(
                URL::to(
                    'product/' . $product->seo_slug . '-' . $product->product_id
                ),
                $product->updated_at,
                '0.8',
                'daily'
            );
        }

        // get all categories from db
        $categories = DB::table('categories')
            ->where('hidden', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // add every category to the sitemap
        foreach ($categories as $category) {
            $seo = preg_replace('![^a-z0-9]+!i', '-', $category->name); // replace all symbols by -
            $seo = strtolower($seo); // transform to lower case
            $seo = str_replace('--', '-', $seo); // remove repeated dashses that replaced the symbols and spaces
            $seo = $seo . '-' . $category->category_id; // add the product id at the end
            $seo = str_replace('--', '-', $seo); // execute twice if repeated characters still appear

            $sitemap->add(
                URL::to('product-list/' . $seo),
                $category->updated_at,
                '0.9',
                'monthly'
            );
        }

        // get all tags from db
        $tags = DB::table('tags')
            ->where('hidden', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // add every tag to the sitemap
        foreach ($tags as $tag) {
            $seo = preg_replace('![^a-z0-9]+!i', '-', $tag->name); // replace all symbols by -
            $seo = strtolower($seo); // transform to lower case
            $seo = str_replace('--', '-', $seo); // remove repeated dashses that replaced the symbols and spaces
            $seo = $seo . '-' . $tag->tag_id; // add the product id at the end
            $seo = str_replace('--', '-', $seo); // execute twice if repeated characters still appear

            $sitemap->add(
                URL::to('collections/' . $seo),
                $tag->updated_at,
                '0.9',
                'monthly'
            );
        }

        // get all brands from db
        $brands = DB::table('brands')
            ->where('hidden', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // add every brand to the sitemap
        foreach ($brands as $brand) {
            $seo = preg_replace('![^a-z0-9]+!i', '-', $brand->name); // replace all symbols by -
            $seo = strtolower($seo); // transform to lower case
            $seo = str_replace('--', '-', $seo); // remove repeated dashses that replaced the symbols and spaces
            $seo = $seo . '-' . $brand->brand_id; // add the product id at the end
            $seo = str_replace('--', '-', $seo); // execute twice if repeated characters still appear

            $sitemap->add(
                URL::to('all-brands/' . $seo),
                $brand->updated_at,
                '0.5',
                'monthly'
            );
        }
    }

    // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
    return $sitemap->render('xml');
});

// db integration
// ========================
// Route::get('/db-{prod}', [
//     'as' => 'db_path',
//     'uses' => 'ProductController@dbMultiple'
// ]);
