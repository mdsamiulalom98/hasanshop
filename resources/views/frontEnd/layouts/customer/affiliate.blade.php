@extends('frontEnd.layouts.master')
@section('title','Affiliate')
@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="customer-sidebar">
                    @include('frontEnd.layouts.customer.sidebar')
                </div>
            </div>
            <div class="col-sm-9">
                <div class="customer-content checkout-shipping">
                    <h5 class="account-title">Affiliate Request Form</h5>
                    <form action="{{route('customer.affiliate_store')}}" method="POST" class="row" enctype="multipart/form-data" data-parsley-validate="">
                        @csrf
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$profile_edit->name}}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="phone">Phone Number *</label>
                                <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{$profile_edit->phone}}"  required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$profile_edit->email}}"  required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="address">Address *</label>
                                <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{$profile_edit->address}}"  required>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="district">District *</label>
                                <select  id="district" class="form-control select2 district @error('district') is-invalid @enderror" name="district" value="{{ old('district') }}"  required>
                                    <option value="">Select...</option>
                                    @foreach($districts as $key=>$district)
                                    <option value="{{$district->district}}" @if($profile_edit->district==$district->district) selected @endif>{{$district->district}}</option>
                                    @endforeach
                                </select>
                                @error('district')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="area">Area *</label>
                                <select  id="area" class="form-control area select2 @error('area') is-invalid @enderror" name="area" value="{{ old('area') }}"  required>
                                    <option value="">Select...</option>
                                    @foreach($areas as $key=>$area)
                                    <option value="{{$area->id}}" @if($profile_edit->area == $area->id) selected @endif>{{$area->area_name}}</option>
                                    @endforeach
                                    
                                </select>
                                @error('area')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <!-- col-end -->
                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="nid1" class="form-label">Nid Font Image *</label>
                                <input type="file" class="form-control @error('nid1') is-invalid @enderror"
                                    name="nid1" value="{{ old('nid1') }}" id="nid1" required="">
                                @error('nid1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <!-- col-end -->
                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="nid2" class="form-label">Nid Back Image *</label>
                                <input type="file" class="form-control @error('nid2') is-invalid @enderror"
                                    name="nid2" value="{{ old('nid2') }}" id="nid2" required="">
                                @error('nid2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <button type="submit" class="submit-btn">Send Request</button>
                            </div>
                        </div>
                        <!-- col-end -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/form-validation.init.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

 <script>
    $('.district').on('change',function(){
    var id = $(this).val();
        $.ajax({
           type:"GET",
           data:{'id':id},
           url:"{{route('districts')}}",
           success:function(res){               
            if(res){
                $(".area").empty();
                $(".area").append('<option value="">Select..</option>');
                $.each(res,function(key,value){
                    $(".area").append('<option value="'+key+'" >'+value+'</option>');
                });
           
            }else{
               $(".area").empty();
            }
           }
        });  
   });
</script>

@endpush