@extends('layout.dashboard',['page_title' => 'Create A Review Page'])
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Manage reviews', 'router'=>'products.list'])
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Create Review Page'])
@endsection
@section('body_content')
    <div class="clearfix"></div>
    <!-- Content -->
    <div class="wrapper-space wrapper-space--45 bg-white create-reviews-page">
        <h1 class="ali-title-normal color-dark-blue mar-t-0 mar-b-15">Creating Reviews Page</h1>
        <p class="ali-body-medium mar-b-30">Support to create a review page that can show entire reviews on the store in the same place:</p>

        <div class="row">
            <div class="col-md-7">

                @if(empty($page_reviews))
                    <form class="mar-b-50"  id="form-create-reviews-page">
                        <div class="input-group">
                            <input type="text" class="form-control" name="title" placeholder="Page name">
                            <span class="input-group-btn">
                                <input type="submit" class="button button--primary fw500 fz13 _btn-add-page" value="Add Page">
                            </span>
                        </div>
                        <!-- /input-group -->
                    </form>
                @else
                    <div class="ars-field mar-b-25">
                        <label class="ars-field-title mar-r-10">Your page:</label>
                        <a target="_blank" href="https://{{ session('shopDomain').'/pages/'.$page_reviews->handle }}">{{ $page_reviews->title }}</a>

                        <a href="https://{{ session('shopDomain').'/admin/pages/'.$page_reviews->id }}" target="_blank" class="page-shopify-admin">
                            <i class="material-icons">launch</i>
                        </a>
                    </div>
                @endIf
            </div>
        </div>

        <p class="ali-body-medium mar-b-30 fw600">Or paste the code below into the available page:</p>

        <div class="row mar-b-15">
            <div class="col-md-7">
                <textarea name="" rows="5" class="form-control vertical-resize"><div id="shopify-ali-review" product-id="0" products-not-in=""><div class="shop_info" shop-id="{{ session("shopId") }}"><div class="reviews"></div></div></div></textarea>
            </div>
        </div>
    </div>
@endsection
