@extends('frontEnd.layouts.master')
@section('title', $subcategory->name)
@push('seo')
    <meta name="app-url" content="{{ route('subcategory', $subcategory->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $subcategory->meta_description }}" />
    <meta name="keywords" content="{{ $subcategory->slug }}" />
    
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{ $subcategory->name }}" />
    <meta name="twitter:title" content="{{ $subcategory->name }}" />
    <meta name="twitter:description" content="{{ $subcategory->meta_description }}" />
    <meta name="twitter:creator" content="{{$generalsetting->name}}" />
    <meta property="og:url" content="{{ route('subcategory', $subcategory->slug) }}" />
    <meta name="twitter:image" content="{{ asset($subcategory->image) }}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $subcategory->name }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('subcategory', $subcategory->slug) }}" />
    <meta property="og:image" content="{{ asset($subcategory->image) }}" />
    <meta property="og:description" content="{{ $subcategory->meta_description }}" />
    <meta property="og:site_name" content="{{ $subcategory->name }}" />
@endpush
@section('content')
    <section class="product-section">
        <div class="container">
            <div class="sorting-section">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="category-breadcrumb d-flex align-items-center">
                            <a href="{{ route('home') }}">Home</a>
                            <span>/</span>
                            <strong>{{ $subcategory->name }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="showing-data">
                                    <span>Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                                        {{ $products->total() }} Results</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="filter_sort">
                                    <div class="page-sort">
                                        @include('frontEnd.layouts.partials.sort_form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="category-product {{$products->total() == 0 ? 'no-product' : ''}}">
                        @forelse($products as $key => $value)
                            <div class="product_item wist_item">
                                @include('frontEnd.layouts.partials.product')
                            </div>
                        @empty
                        <div class="no-found">
                            <img src="{{asset('public/frontEnd/images/not-found.png')}}" alt="">
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_paginate">
                        {{ $products->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection