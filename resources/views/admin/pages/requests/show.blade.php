@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')
<div class="row">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col-lg-8">
        <div class="">
            <h3 style="background: #c3d87d;padding:10px;">
                @lang('Information')
            </h3>
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Club Name Arabic')
                            </td>
                            <td>
                                {{ $request->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Club Name English')
                            </td>
                            <td>
                                {{ $request->name_en ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Club About Arabic')
                            </td>
                            <td>
                                {{ $request->about ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Club About English')
                            </td>
                            <td>
                                {{ $request->about_en ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                @lang('Join At')
                            </td>
                            <td>
                                {{ $request->created_at }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                @lang('Categories')
                            </td>
                            <td>
                                <ul>
                                    @foreach ($request->categories()->get() as $cat)
                                        <li>
                                            {{ $cat->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="">
            <h3 style="background: #c3d87d;padding:10px;">
                @lang('Status')
            </h3>
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Club Status')
                            </td>
                            <td>
                                <div class="hstack gap-2 fs-15">
                                    <a href="{{ route('admin.requests.accept',$request->id) }}" class="btn btn-icon btn-sm btn-success-transparent rounded-pill modelAccepted" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ __('Accept Club') }}">
                                        <i class="fe fe-check-circle"></i>
                                    </a>
                                    <a href="{{ route('admin.requests.reject',$request->id) }}" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill modelRejected" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ __('Reject Club') }}">
                                        <i class="fe fe-x-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="">

            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Email')
                            </td>
                            <td>
                                <a href="mailto:{{ $request->email }}">{{ $request->email }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Phone')
                            </td>
                            <td>
                                <a href="tel:{{ $request->phone }}">{{ $request->phone }}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="">

            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Club Logo')
                            </td>
                            <td>
                                <img src="{{ $request->display_image }}" style="width: 80px;">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="AcceptedModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">@lang('Accept This Club')</h6>
                <button aria-label="Close" class="btn btn-sm" data-bs-dismiss="modal" type="button">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <form id="AcceptedModalForm" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Club Commission',
                                'name' => 'vat',
                                'type' => 'number',
                                'required' => true,
                                'value' => null
                            ])
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="button-group">
                        @csrf
                        <button class="btn ripple btn-primary" type="submit">@lang('Save Action')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="RejectedModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">@lang('Reject This Club')</h6>
                <button aria-label="Close" class="btn btn-sm" data-bs-dismiss="modal" type="button">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <form id="RejectedModalForm" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.component.form_fields.textarea', [
                                'label' => 'Reject Message',
                                'name' => 'rejected_message',
                                'required' => true,
                                'value' => null
                            ])
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="button-group">
                        @csrf
                        <button class="btn ripple btn-primary" type="submit">@lang('Save Action')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script>
        $(".modelAccepted").on("click",function(e){
            e.preventDefault();
            var link = $(this).attr("href");
            $("#AcceptedModalForm").attr("action",link);
            $("#AcceptedModal").modal("show");
        });
        $(".modelRejected").on("click",function(e){
            e.preventDefault();
            var link = $(this).attr("href");
            $("#RejectedModalForm").attr("action",link);
            $("#RejectedModal").modal("show");
        });
    </script>
@endpush
