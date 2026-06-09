@extends('layouts.app')
@push('styles')
@endpush

@section('content')
<!-- Content -->
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="pull-right">
                <a class="btn btn-outline-secondary" href="{{ route('roles.index', withLang()) }}"><i class='bx bxs-chevrons-left'></i>&nbsp; Back</a>
            </div>
        </div>
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Role</h5>
                </div>
                <div class="card-body">
                    {!! Form::open(array('route' => ['roles.store', withLang()] ,'method'=>'POST')) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-fullname">Name</label>
                                <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter your role" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">

                            <div class="form-group">

                                <label class="form-label">Permission</label>
                                <br />

                                @php

                                $groups = [];

                                foreach($permission as $perm){

                                $group = explode('-', $perm->name)[0];

                                $groups[$group][] = $perm;
                                }

                                @endphp


                                @foreach($groups as $groupName => $permissions)

                                {{-- Parent Permission --}}
                                <div class="mb-2 mt-3">

                                    <input type="checkbox"
                                        class="form-check-input parent-permission"
                                        data-group="{{ $groupName }}"
                                        id="parent-{{ $groupName }}">

                                    <label for="parent-{{ $groupName }}">
                                        <strong>{{ ucfirst($groupName) }}</strong>
                                    </label>

                                </div>


                                {{-- Child Permission --}}
                                @foreach($permissions as $value)

                                <div style="margin-left: 30px;" class="mb-1">

                                    <input type="checkbox"
                                        name="permission[]"
                                        value="{{ $value->id }}"
                                        class="form-check-input child-permission"
                                        data-group="{{ $groupName }}"
                                        id="permission-{{ $value->id }}">

                                    <label for="permission-{{ $value->id }}">
                                        {{ $value->name }}
                                    </label>

                                </div>

                                @endforeach

                                @endforeach

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

    @endsection
    @push('script')
    
    <script src="{{ asset('assets/js/permission.js') }}"></script>
    <script>
        
        function submitForm() {
            $('.submit-delete').click();
        }
    </script>
    @endpush
