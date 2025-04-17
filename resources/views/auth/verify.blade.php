@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Verify Your Email</h2>

                    <!-- Display global error message if there's any
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif -->
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('verify.code') }}">
                        @csrf


                        <div class="form-group">
                            <label for="verification_code">Verification Code</label>
                            <input type="text" name="verification_code" class="form-control @error('verification_code') is-invalid @enderror" value="{{ old('verification_code') }}">

                            @error('verification_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="mt-2 btn btn-primary">Verify Code</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection