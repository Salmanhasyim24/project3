@extends('admin.dashboard')
@section('title', 'Edit Category')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Update New Category</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update New Category</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">Update New Category</h5>
                <hr />

                <form id="myForm" method="post" action="{{ route('update.category', $category->id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $category->id }}">
                    <input type="hidden" name="image" value="{{ $category->image }}">

                    <div class="form-body mt-4">
                        <div class="row">
                            <div class="col-lg-12 col-6">
                                <div class="border border-3 p-4 rounded">

                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $category->name) }}"
                                            placeholder="Masukkan Judul Post">
                                        <!-- error message untuk name -->
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <input type="submit" class="btn btn-primary px-4" value="Save Changes" />

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
            </div>

            </form>

        </div>

    </div>



    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: 'Please Enter Product Name',
                    },

                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>



@endsection
