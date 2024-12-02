@extends('backEnd.layouts.master') 
@section('title','Slider Create') 
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('sliders.index')}}" class="btn btn-primary waves-effect waves-light btn-sm rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Slider Create</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('sliders.store')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf  
                        <!-- =======start-Title======== -->
                        <div class="form-group">
                            <label for="title" class="my-2">Title *</label>
                            <input type="text" id="title" name="title" class="form-control">
                        </div>
                        <!-- =======end-Title======== -->
                        <!-- =======start-sub-Title======== -->
                        <div class="form-group">
                            <label for="subtitle" class="my-2">Sub Title *</label>
                            <input type="text" id="subtitle" name="subtitle" class="form-control">
                        </div>
                        <!-- =======sub-end-Title======== -->
                        <!-- =======start-button======== -->
                        <div class="form-group">
                            <label for="link" class="my-2">Button Link *</label>
                            <input type="text" id="link" name="link" class="form-control">
                        </div>
                        <!-- =======sub-Button======== -->                       
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="image" class="form-label">Image * (380x400px)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image') }}"  id="image" required="">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        
                        
                        <!-- col-end -->
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="status" class="d-block">Status</label>
                                <label class="switch">
                                    <input type="checkbox" value="1" name="status" checked />
                                    <span class="slider round"></span>
                                </label>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div>
                            <input type="submit" class="btn btn-success" value="Submit" />
                        </div>
                    </form>
                </div>
                <!-- end card-body-->
            </div>
            <!-- end card-->
        </div>
        <!-- end col-->
    </div>
</div>
@endsection 
@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection
