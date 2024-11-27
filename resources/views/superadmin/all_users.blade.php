@extends('layouts.layout_super_admin')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">รายชื่อบัญชีผู้ใช้งาน</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-right mb-3">
        <a href="{{ route('pending_users') }}" class="btn btn-warning">
            <i class="fas fa-clock"></i> บัญชีที่รออนุมัติ
        </a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ชื่อบัญชีผู้ใช้งาน</th>
                <th>อีเมลของผู้ใช้งาน</th>
                <th>ประเภทผู้ใช้งาน</th>
                <th>สถานะบัญชีผู้ใช้งาน</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->role_name ?? 'No Role' }}</td>
                    <td>
                        <span class="badge {{ $user->is_approved ? 'bg-success' : 'bg-warning' }}">
                            {{ $user->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection