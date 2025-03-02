@extends('layouts.admin.app')

@section('title', translate('Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/system-setting.png')}}" alt="{{ translate('system_setting_image') }}">
                {{translate('system_setup')}}
            </h2>
        </div>

        <div class="card">
            <div class="px-20 py-3 d-flex flex-wrap gap-3 justify-content-between">
                <h5 class="d-flex align-items-center gap-2 mb-0">
                    {{translate('films_List')}}
                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $customers->total() }}</span>
                </h5>
                <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-password" title="Add Customer">
                    <i class="tio-add"></i>
                </button>

            </div>
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('created_at')}}</th>
                            <th>{{translate('file')}}</th>
                            <th  class="text-center">{{translate('actions')}}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>
                                {{$customer->id}}
                            </td>
                            <td>
                                {{$customer['created_at']}}
                            </td>
                            <td>
                                {{$customer['file']}}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-primary btn-sm square-btn" href="{{route('admin.business-settings.download-backup',[$customer['id']])}}">
                                        <i class="tio-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $customers->links() !!}
                </div>
            </div>
            @if(count($customers)==0)
                <div class="text-center p-4">
                    <img class="mb-3 width-7rem" src="{{asset('public/assets/admin//svg/illustrations/sorry.svg')}}" alt="{{ translate('image') }}">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="add-password" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_backup')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.business-settings.generate-backup') }}" method="post" id="customer-form">
                        @csrf
                        add new backup
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
