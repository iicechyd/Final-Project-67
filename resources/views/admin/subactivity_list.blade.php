@extends('layouts.layout_admin')
@section('title', 'กิจกรรมย่อย')
@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('css/subactivity_list.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div>
        <h1 class="table-heading text-center">หลักสูตร</h1>
        <button type="button" class="btn my-3"
            style="background-color: rgb(85, 88, 218); border-color: rgb(85, 88, 218); color: white;" data-toggle="modal"
            data-target="#InsertSubacitivtyModal">
            เพิ่มหลักสูตร
        </button>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th data-type="text-long">ชื่อกิจกรรม<span class="resize-handle"></span></th>
                        <th data-type="text-short">หลักสูตร<span class="resize-handle"></span></th>
                        <th data-type="text-short">สถานะ<span class="resize-handle"></span></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentActivityId = null;
                    @endphp
                    @foreach ($subActivities as $subActivity)
                        @if ($currentActivityId != $subActivity->activity_id)
                            @php
                                $currentActivityId = $subActivity->activity_id;
                            @endphp
                            <tr>
                                <td
                                    rowspan="{{ $subActivities->where('activity_id', $subActivity->activity_id)->count() }}">
                                    {{ $subActivity->activity->activity_name }}<br>
                                    จำนวนหลักสูตรที่เลือกได้ {{ $subActivity->activity->max_subactivities }} หลักสูตร
                                    <i class="fas fa-edit" style="color: #3b44ff;" data-id="{{ $subActivity->activity_id }}"
                                        data-max="{{ $subActivity->activity->max_subactivities }}"></i>
                                </td>
                                <td>{{ $subActivity->sub_activity_name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0);" class="toggle-status"
                                        data-id="{{ $subActivity->sub_activity_id }}"
                                        data-name="{{ $subActivity->sub_activity_name }}"
                                        data-status="{{ $subActivity->status }}">
                                        @if ($subActivity->status === 'active')
                                            <i class="fas fa-toggle-on text-success" style="font-size: 24px;"
                                                title="Active"></i>
                                        @else
                                            <i class="fas fa-toggle-off text-secondary" style="font-size: 24px;"
                                                title="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $subActivity->sub_activity_name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0);" class="toggle-status"
                                        data-id="{{ $subActivity->sub_activity_id }}"
                                        data-name="{{ $subActivity->sub_activity_name }}"
                                        data-status="{{ $subActivity->status }}">
                                        @if ($subActivity->status === 'active')
                                            <i class="fas fa-toggle-on text-success" style="font-size: 24px;"
                                                title="Active"></i>
                                        @else
                                            <i class="fas fa-toggle-off text-secondary" style="font-size: 24px;"
                                                title="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal เพิ่มหลักสูตร-->
    <div class="modal fade" id="InsertSubacitivtyModal" tabindex="-1" role="dialog"
        aria-labelledby="InsertSubacitivtyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="InsertSubacitivtyModalLabel">เพิ่มหลักสูตร</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.storeSubActivity') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="activity_id">เลือกกิจกรรม</label>
                            <select name="activity_id" id="activity_id" class="form-control" required>
                                <option value="">เลือกประเภทกิจกรรม</option>
                                @foreach ($activities as $activity)
                                    <option value="{{ $activity->activity_id }}">{{ $activity->activity_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sub_activity_name">ชื่อหลักสูตร</label>
                            <input type="text" class="form-control" id="sub_activity_name" name="sub_activity_name"
                                required>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal เพิ่มจำนวนหลักสูตรที่เลือกได้ -->
    <div class="modal fade" id="EditMaxSubactivitiesModal" tabindex="-1" role="dialog"
        aria-labelledby="EditMaxSubactivitiesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditMaxSubactivitiesModalLabel">แก้ไขจำนวนหลักสูตรที่เลือกได้</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMaxSubactivitiesForm" action="{{ route('admin.updateMaxSubactivities') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="activity_id" id="editActivityId">
                        <div class="form-group">
                            <label for="max_subactivities">จำนวนหลักสูตรที่เลือกได้</label>
                            <input type="number" class="form-control" id="maxSubactivitiesInput"
                                name="max_subactivities" min="0" required>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/subactivity_list.js') }}"></script>
@endsection
