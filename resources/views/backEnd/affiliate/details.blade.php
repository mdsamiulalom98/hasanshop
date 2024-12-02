@extends('backEnd.layouts.master')
@section('title','Affiliates Details')

@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title text-capitalize"> Affiliates Details</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
               <table class="table table-responsive">
                   <tbody>
                            <tr class="text-muted mb-2 font-13">
                                <td>Status </td>
                                <td class="ms-2">{{$show_data->status}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Full Name </td>
                                <td class="ms-2">{{$show_data->name}}</td>
                            </tr>

                            <tr class="text-muted mb-2 font-13">
                                <td>Mobile </td>
                                <td class="ms-2">{{$show_data->phone}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Email </td>
                                <td class="ms-2">{{$show_data->email}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>District </td>
                                <td class="ms-2">{{$show_data->district}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Address </td>
                                <td class="ms-2">{{$show_data->address}}</td>
                            </tr>
                            
                   </tbody>
               </table>
                
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <form action="{{route('affiliates.request_status')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$show_data->id}}" name="id">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status *</label>
                             <select class="form-control"  name="status" required>
                                <option value="">Select..</option>
                                <option value="active" {{$show_data->status == 'active' ? 'selected' :''}}>Active</option>
                                <option value="cancelled" {{$show_data->status == 'cancelled' ? 'selected' :''}}>Cancelled</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div><!-- end col-->
   </div>
</div>
@endsection