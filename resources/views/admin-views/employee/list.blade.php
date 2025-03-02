@extends('layouts.admin.app')

@section('title', translate('Employee List'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3" style="display: flex; width: 100%;justify-content: space-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{translate('Dashboard')}}
                <i class="tio-chevron-right"></i>
                <span style="color: var(--blue);">
                    {{translate('employee')}}
                </span>
            </h2>
            <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal"
                data-target="#add-employee" title="Add Employee">
                <i class="tio-add"></i>
                {{translate('employee')}}
            </button>
        </div>
        <div class="card">
            <div class="px-20 py-3 d-flex flex-wrap gap-3 justify-content-between">
                <h5 class="d-flex align-items-center gap-2 mb-0">
                    <img width="20" src="{{asset('public/assets/admin/img/icons/customer.png')}}"
                        alt="{{ translate('employee') }}">
                    {{translate('employee_List')}}
                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $customers->total() }}</span>
                </h5>
                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                            placeholder="{{translate('Search by Name')}}" aria-label="Search" value="{{$search}}" required
                            autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">{{translate('search')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('name')}}</th>
                            <th>{{translate('contact_info')}}</th>
                            <th>{{translate('role')}}</th>
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('actions')}}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @foreach($customers as $key => $customer)
                                        <tr>
                                            <td>
                                                {{$customers->firstitem() + $key}}
                                            </td>
                                            <td>
                                                <div class="text-dark media gap-3 align-items-center">
                                                    <div class="avatar rounded-circle">
                                                        <img class="img-fit rounded-circle" src="{{$customer['image']}}"
                                                            alt="{{ translate('customer') }}">
                                                    </div>
                                                    <div class="media-body">{{$customer['name']}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><a class="text-dark" href="mailto:{{$customer['email']}}">{{$customer['email']}}</a>
                                                </div>
                                                <div><a class="text-dark" href="tel:{{$customer['phone']}}">{{$customer['phone']}}</a></div>
                                            </td>
                                            <td>
                                                {{
                            $customer->user_type == 4
                            ? 'admin'
                            : ($customer->user_type == 3
                                ? 'Super Admin'
                                : 'employee' . ' ' . $customer->branch)
                                                                                                                                                                            }}
                                            </td>
                                            <td>
                                                @if($customer['active'] == 1)
                                                    <label class="switcher">
                                                        <input type="checkbox" class="switcher_input change-status" checked
                                                            data-route="{{route('admin.customer.status', [$customer['id'], 0])}}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                @else
                                                    <label class="switcher">
                                                        <input type="checkbox" class="switcher_input change-status"
                                                            data-route="{{route('admin.customer.status', [$customer['id'], 1])}}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button style="padding: 4px 6px;" class="btn btn-success rounded text-nowrap"
                                                        onclick="fixModelChange('{{$customer['name']}}', '{{$customer['guid']}}')"
                                                        type="button" data-toggle="modal" data-target="#add-password"
                                                        title="change password">
                                                        <i class="tio-password"></i>
                                                    </button>

                                                    <a class="btn btn-outline-danger square-btn form-alert" href="javascript:"
                                                        data-id="banner-{{$customer['id']}}"
                                                        data-message="{{translate('Want to delete this user ?')}}">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                </div>
                                                <form action="{{route('admin.customer.delete', [$customer['id']])}}" method="post"
                                                    id="banner-{{$customer['id']}}">
                                                    @csrf @method('delete')
                                                </form>
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
            @if(count($customers) == 0)
                <div class="text-center p-4">
                    <img class="mb-3 width-7rem" src="{{asset('public/assets/admin//svg/illustrations/sorry.svg')}}"
                        alt="{{ translate('image') }}">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="add-employee" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_Employee')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.staff.store') }}" method="post" id="employee-form">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Name') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ translate('Name') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Phone') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control"
                                        placeholder="{{ translate('Phone') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="role_id">{{translate('role')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="user_type" class="form-control js-select2-custom" id="role_id">
                                        <option value="2">{{ translate('employee') }}</option>
                                        <option value="4">{{ translate('admin') }}</option>
                                        <option value="3">{{ translate('super Admin') }}</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="branch_id">{{translate('branch')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="branch_id" class="form-control " id="branch_id">
                                        <option value="1">{{ translate('beirut') }}</option>
                                        <option value="2">{{ translate('dahye') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('email') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="email" class="form-control"
                                        placeholder="{{ translate('email') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">Password<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">Password Confirmation<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Password Confirmation" required>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-password" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_Employee')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.staff.password') }}" method="post" id="customer-form">
                        @csrf
                        <div class="row pl-2">
                            <input type="hidden" id="guid_change" name="guid" class="form-control" required>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">name</label>
                                    <input type="text" id="name_change" name="name" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">Password<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">Password Confirmation<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Password Confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fixModelChange(name, guid) {
            document.getElementById('name_change').value = name;
            document.getElementById('guid_change').value = guid;
        }
        function fixModelChange2(name, guid) {
            document.getElementById('name_change2').value = name;
            document.getElementById('guid_change2').value = guid;
        }
    </script>
@endsection