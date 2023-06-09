@include('layout.header')


<title>RAV Accreditation</title>

<style>
.error{
     color: red;
}
</style>


</head>



<body class="light">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30">
                <img class="loading-img-spin" src="{{asset('assets/images/favicon.png')}}" alt="admin">
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

       @include('layout.topbar')

    <div>


        @if(Auth::user()->role  == '1' )

        @include('layout.sidebar')

        @elseif(Auth::user()->role  == '2')

        @include('layout.siderTp')

        @elseif(Auth::user()->role  == '3')

        @include('layout.sideAss')

        @elseif(Auth::user()->role  == '4')

        @include('layout.sideprof')

        @endif




        @include('layout.rightbar')


    </div>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <ul class="breadcrumb breadcrumb-style ">
                            <li class="breadcrumb-item">
                                <h4 class="page-title">Edit Level</h4>
                            </li>
                            <li class="breadcrumb-item bcrumb-1">
                                <a href="{{url('/dashboard')}}">
                                    <i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="breadcrumb-item bcrumb-2">
                                <a href="#" onClick="return false;">Level</a>
                            </li>
                            <li class="breadcrumb-item active">Edit Level</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>Edit</strong> Level</h2>
                            {{-- <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu float-end">
                                        <li>
                                            <a href="javascript:void(0);">Action</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Another action</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Something else here</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul> --}}
                        </div>

                        <form action="{{ url('update-level_post'.'/'.dEncrypt($data->id)) }}" method="post">

                            @csrf
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                            <label><strong>level Information</strong></label>
                                        <div class="form-line">
                                            <textarea class="form-control" name="level_Information"  placeholder="Level Information">{{ $data->level_Information }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> <strong>Prerequisites</strong> </label>
                                        <div class="form-line">
                                            <textarea class="form-control" name="Prerequisites"  placeholder="Prerequisites">{{ $data->Prerequisites}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong>Documents Required</strong></label>
                                        <div class="form-line">

                                            <textarea class="form-control" name="documents_required"   placeholder="Documents required">{{ $data->documents_required }}</textarea>
                                        </div>

                                        <label for="documents_required"  id="documents_required-error" class="error">
                                            @error('documents_required')
                                            {{ $message }}
                                        @enderror</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <strong>Validity</strong></label>
                                        <div class="form-line">
                                            {{-- <textarea class="form-control" name="validity"  placeholder="validity">{{ $data->validity }}</textarea> --}}
                                            <input class="form-control" name="validity"  placeholder="validity" value="{{ $data->validity }}" >
                                        </div>
                                    </div>
                                    <label for="validity"  id="validity-error" class="error">
                                        @error('validity')
                                        {{ $message }}
                                    @enderror</label>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><strong>Timelines</strong></label>
                                        <div class="form-line">

                                       <input class="form-control" name="timelines" placeholder="Timelines" value="{{ $data->timelines }}" >

                                        </div>

                                        <label for="timelines"  id="timelines-error" class="error">
                                            @error('timelines')
                                            {{ $message }}
                                            @enderror</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><strong>Fee Structure</strong> </label>
                                        <div class="form-line">

                                            {{-- <textarea class="form-control" name="fee_structure"  placeholder="Fee Structure">{{ $data->fee_structure }}</textarea> --}}
                                            <input class="form-control" name="fee_structure" placeholder="Fee Structure"  value="{{ $data->fee_structure }}" >
                                        </div>
                                        <label for="fee_structure"  id="fee_structure-error" class="error">
                                            @error('fee_structure')
                                            {{ $message }}
                                            @enderror</label>

                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 p-t-20 text-center">
                                <button type="submit" class="btn btn-primary waves-effect m-r-15">Submit</button>
                                <button type="button" class="btn btn-danger waves-effect">Cancel</button>
                            </div>

                         </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @include('layout.footer')
