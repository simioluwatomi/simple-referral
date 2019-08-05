@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                    <ul class="list-group mt-3">
                        <li class="list-group-item">Username: {{ Auth::user()->username }}</li>
                        <li class="list-group-item">Email: {{ Auth::user()->email }}</li>
                        <li class="list-group-item">Referral link: {{ Auth::user()->referral_link }}</li>
                        <li class="list-group-item">Referrer: {{ Auth::user()->referrer->name ?? 'Not Specified' }}</li>
                        <li class="list-group-item">Refferal count: {{ count(Auth::user()->referrals)  ?? '0' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
